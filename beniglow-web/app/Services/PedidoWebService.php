<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\MovimientoInventario;
use App\Models\PedidoWeb;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PedidoWebService
{
    public function crearDesdePayload(array $data): PedidoWeb
    {
        $pedido = DB::transaction(function () use ($data) {
            $empresa = Empresa::first();
            $totales = $this->calcularTotales($data['items'], $empresa, (float) ($data['descuento'] ?? 0), (float) ($data['envio'] ?? 0));
            $cliente = $this->resolverCliente($data);

            $pedido = PedidoWeb::create([
                'codigo' => $this->generarCodigoPedido(),
                'cliente_id' => $cliente?->id,
                'canal' => 'web',
                'origen' => $data['origen'] ?? 'storefront',
                'estado' => 'pendiente_pago',
                'estado_pago' => 'pendiente',
                'estado_stock' => 'sin_descontar',
                'subtotal' => $totales['subtotal'],
                'descuento' => $totales['descuento'],
                'impuesto' => $totales['impuesto'],
                'envio' => $totales['envio'],
                'total' => $totales['total'],
                'moneda' => $empresa?->codigo_moneda ?: 'PEN',
                'metodo_pago' => $data['metodo_pago'] ?? null,
                'referencia_pago' => $data['referencia_pago'] ?? null,
                'payment_payload' => $data['payment_payload'] ?? null,
                'cliente_nombre' => $data['cliente']['nombres'] ?? $cliente?->nombre_completo ?? 'Cliente web',
                'cliente_email' => $data['cliente']['email'] ?? $cliente?->email,
                'cliente_telefono' => $data['cliente']['telefono'] ?? $cliente?->telefono,
                'cliente_documento' => $data['cliente']['documento'] ?? $cliente?->documento,
                'direccion_envio' => $data['direccion_envio'] ?? null,
                'notas' => $data['notas'] ?? null,
            ]);

            foreach ($totales['items'] as $item) {
                $producto = $item['producto'];

                $pedido->detalles()->create([
                    'producto_id' => $producto->id,
                    'codigo' => $producto->codigo,
                    'nombre' => $producto->nombre,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'descuento' => 0,
                    'impuesto' => $item['impuesto'],
                    'subtotal' => $item['subtotal'],
                    'total' => $item['total'],
                    'meta' => [
                        'slug' => $producto->slug,
                        'marca' => $producto->marca,
                        'linea' => $producto->linea,
                        'tono' => $producto->tono,
                        'presentacion' => $producto->presentacion,
                    ],
                ]);
            }

            return $pedido->load('detalles.producto', 'cliente');
        });

        if (($data['confirmar_pago'] ?? false) || (($data['estado_pago'] ?? null) === 'pagado')) {
            return $this->confirmarPago($pedido, $data);
        }

        return $pedido;
    }

    public function confirmarPago(PedidoWeb $pedidoWeb, array $data = []): PedidoWeb
    {
        return DB::transaction(function () use ($pedidoWeb, $data) {
            $pedido = PedidoWeb::with('detalles')
                ->whereKey($pedidoWeb->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($pedido->estado === 'cancelado') {
                throw ValidationException::withMessages([
                    'pedido' => 'No se puede confirmar un pedido cancelado.',
                ]);
            }

            if ($pedido->estado_pago === 'pagado' && $pedido->estado_stock === 'descontado') {
                return $pedido->load('detalles.producto', 'cliente', 'venta');
            }

            $productos = Producto::whereIn('id', $pedido->detalles->pluck('producto_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($pedido->detalles->groupBy('producto_id') as $productoId => $detallesProducto) {
                $detalle = $detallesProducto->first();
                $producto = $productos->get($detalle->producto_id);
                $cantidadTotal = $detallesProducto->sum(fn ($linea) => (float) $linea->cantidad);

                if (! $producto || ! $producto->activo || ! $producto->visible_web) {
                    throw ValidationException::withMessages([
                        'items' => "El producto {$detalle->nombre} ya no esta disponible.",
                    ]);
                }

                if ($producto->controla_stock && $producto->stock < $cantidadTotal) {
                    throw ValidationException::withMessages([
                        'items' => "Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock}.",
                    ]);
                }
            }

            $venta = $this->crearVentaDesdePedido($pedido, $data);

            foreach ($pedido->detalles as $detalle) {
                $producto = $productos->get($detalle->producto_id);

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'codigo' => $producto->codigo,
                    'descripcion' => $producto->nombre,
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_unitario,
                    'descuento' => $detalle->descuento,
                    'impuesto' => $detalle->impuesto,
                    'subtotal' => $detalle->subtotal,
                    'total' => $detalle->total,
                ]);

                if ($producto->controla_stock) {
                    $stockAnterior = $producto->stock;
                    $stockNuevo = $stockAnterior - $detalle->cantidad;
                    $producto->update(['stock' => $stockNuevo]);

                    MovimientoInventario::create([
                        'producto_id' => $producto->id,
                        'user_id' => $venta->user_id,
                        'tipo' => 'salida',
                        'motivo' => 'Pedido web #' . $pedido->codigo,
                        'cantidad' => $detalle->cantidad,
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $stockNuevo,
                        'referencia_tipo' => 'pedido_web',
                        'referencia_id' => $pedido->id,
                        'fecha' => now(),
                    ]);
                }
            }

            $pedido->update([
                'venta_id' => $venta->id,
                'estado' => 'preparando',
                'estado_pago' => 'pagado',
                'estado_stock' => 'descontado',
                'metodo_pago' => $data['metodo_pago'] ?? $pedido->metodo_pago,
                'referencia_pago' => $data['referencia_pago'] ?? $pedido->referencia_pago,
                'payment_payload' => $data['payment_payload'] ?? $pedido->payment_payload,
                'confirmed_at' => now(),
            ]);

            return $pedido->fresh(['detalles.producto', 'cliente', 'venta']);
        });
    }

    private function calcularTotales(array $items, ?Empresa $empresa, float $descuento, float $envio): array
    {
        $productos = $this->obtenerProductosParaItems($items);
        $tasa = $empresa ? ((float) $empresa->impuesto / 100) : 0;
        $impuestoIncluido = $empresa ? (bool) $empresa->impuesto_incluido : true;

        $lineas = [];
        $subtotal = 0;
        $impuesto = 0;
        $totalProductos = 0;

        $cantidadesPorProducto = [];

        foreach ($items as $item) {
            $producto = $productos->get((string) ($item['producto_id'] ?? $item['slug']));
            $cantidad = (float) $item['cantidad'];
            $cantidadesPorProducto[$producto->id] = ($cantidadesPorProducto[$producto->id] ?? 0) + $cantidad;

            if ($producto->controla_stock && $producto->stock < $cantidadesPorProducto[$producto->id]) {
                throw ValidationException::withMessages([
                    'items' => "Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock}.",
                ]);
            }

            $precioUnitario = round((float) $producto->precio_final_web, 2);
            $importe = round($cantidad * $precioUnitario, 2);
            $lineaImpuesto = 0;
            $lineaSubtotal = $importe;
            $lineaTotal = $importe;

            if ($producto->aplica_impuesto && $tasa > 0) {
                if ($impuestoIncluido) {
                    $lineaSubtotal = round($importe / (1 + $tasa), 2);
                    $lineaImpuesto = round($importe - $lineaSubtotal, 2);
                    $lineaTotal = $importe;
                } else {
                    $lineaImpuesto = round($importe * $tasa, 2);
                    $lineaTotal = round($importe + $lineaImpuesto, 2);
                }
            }

            $subtotal += $lineaSubtotal;
            $impuesto += $lineaImpuesto;
            $totalProductos += $lineaTotal;

            $lineas[] = [
                'producto' => $producto,
                'cantidad' => $cantidad,
                'precio_unitario' => $precioUnitario,
                'subtotal' => $lineaSubtotal,
                'impuesto' => $lineaImpuesto,
                'total' => $lineaTotal,
            ];
        }

        $descuento = max(0, $descuento);
        $envio = max(0, $envio);

        return [
            'items' => $lineas,
            'subtotal' => round($subtotal, 2),
            'descuento' => round($descuento, 2),
            'impuesto' => round($impuesto, 2),
            'envio' => round($envio, 2),
            'total' => round(max(0, $totalProductos - $descuento + $envio), 2),
        ];
    }

    private function obtenerProductosParaItems(array $items)
    {
        $ids = collect($items)->pluck('producto_id')->filter()->values();
        $slugs = collect($items)->pluck('slug')->filter()->values();

        $productos = Producto::query()
            ->where('activo', true)
            ->where('visible_web', true)
            ->where(function ($query) use ($ids, $slugs) {
                if ($ids->isNotEmpty()) {
                    $query->orWhereIn('id', $ids);
                }

                if ($slugs->isNotEmpty()) {
                    $query->orWhereIn('slug', $slugs);
                }
            })
            ->get();

        $map = collect();

        foreach ($productos as $producto) {
            $map->put((string) $producto->id, $producto);
            $map->put((string) $producto->slug, $producto);
        }

        foreach ($items as $item) {
            $key = (string) ($item['producto_id'] ?? $item['slug']);

            if (! $map->has($key)) {
                throw ValidationException::withMessages([
                    'items' => 'Uno de los productos enviados no existe o no esta visible en la web.',
                ]);
            }
        }

        return $map;
    }

    private function resolverCliente(array $data): ?Cliente
    {
        if (! empty($data['cliente_id'])) {
            return Cliente::find($data['cliente_id']);
        }

        $clienteData = $data['cliente'] ?? [];

        if (empty($clienteData)) {
            return null;
        }

        $cliente = null;

        if (! empty($clienteData['email'])) {
            $cliente = Cliente::where('email', $clienteData['email'])->first();
        }

        if (! $cliente && ! empty($clienteData['documento'])) {
            $cliente = Cliente::where('documento', $clienteData['documento'])->first();
        }

        if ($cliente) {
            $cliente->update([
                'nombres' => $clienteData['nombres'] ?? $cliente->nombres,
                'apellidos' => $clienteData['apellidos'] ?? $cliente->apellidos,
                'telefono' => $clienteData['telefono'] ?? $cliente->telefono,
                'direccion' => $data['direccion_envio']['direccion'] ?? $cliente->direccion,
                'ciudad' => $data['direccion_envio']['ciudad'] ?? $cliente->ciudad,
                'activo' => true,
            ]);

            return $cliente;
        }

        return Cliente::create([
            'codigo' => $this->generarCodigoCliente(),
            'tipo_documento' => $clienteData['tipo_documento'] ?? 'DNI',
            'documento' => $clienteData['documento'] ?? null,
            'nombres' => $clienteData['nombres'] ?? 'Cliente',
            'apellidos' => $clienteData['apellidos'] ?? null,
            'telefono' => $clienteData['telefono'] ?? null,
            'email' => $clienteData['email'] ?? null,
            'direccion' => $data['direccion_envio']['direccion'] ?? null,
            'ciudad' => $data['direccion_envio']['ciudad'] ?? null,
            'activo' => true,
        ]);
    }

    private function crearVentaDesdePedido(PedidoWeb $pedido, array $data): Venta
    {
        $numeroTicket = $this->generarNumeroVentaWeb();
        $metodoPago = $data['metodo_pago'] ?? $pedido->metodo_pago ?? 'web';

        return Venta::create([
            'numero_ticket' => $numeroTicket,
            'tipo_comprobante' => 'WEB',
            'serie' => 'WEB',
            'fecha_venta' => now(),
            'canal' => 'web',
            'referencia_externa' => $pedido->codigo,
            'cliente_id' => $pedido->cliente_id,
            'user_id' => $this->usuarioSistemaId(),
            'turno_caja_id' => null,
            'subtotal' => $pedido->subtotal,
            'descuento' => $pedido->descuento,
            'impuesto' => $pedido->impuesto,
            'total' => $pedido->total,
            'monto_recibido' => $pedido->total,
            'cambio' => 0,
            'forma_pago' => $metodoPago,
            'detalle_pago' => [
                'referencia_pago' => $data['referencia_pago'] ?? $pedido->referencia_pago,
                'origen' => $pedido->origen,
            ],
            'estado_pago' => 'pagado',
            'estado_envio' => 'preparando',
            'direccion_envio' => $pedido->direccion_envio,
            'estado' => 'completada',
            'observaciones' => 'Pedido web ' . $pedido->codigo,
        ]);
    }

    private function generarCodigoPedido(): string
    {
        $prefijo = 'WEB-' . now()->format('Ymd') . '-';
        $ultimo = PedidoWeb::where('codigo', 'like', $prefijo . '%')
            ->orderByDesc('id')
            ->value('codigo');
        $numero = $ultimo ? ((int) substr($ultimo, -6)) + 1 : 1;

        return $prefijo . str_pad((string) $numero, 6, '0', STR_PAD_LEFT);
    }

    private function generarNumeroVentaWeb(): string
    {
        $ultimo = Venta::where('numero_ticket', 'like', 'WEB-%')
            ->orderByDesc('id')
            ->value('numero_ticket');
        $numero = $ultimo ? ((int) substr($ultimo, -8)) + 1 : 1;

        return 'WEB-' . str_pad((string) $numero, 8, '0', STR_PAD_LEFT);
    }

    private function generarCodigoCliente(): string
    {
        $ultimo = Cliente::where('codigo', 'like', 'CW%')
            ->orderByDesc('id')
            ->value('codigo');
        $numero = $ultimo ? ((int) substr($ultimo, 2)) + 1 : 1;

        return 'CW' . str_pad((string) $numero, 6, '0', STR_PAD_LEFT);
    }

    private function usuarioSistemaId(): int
    {
        $userId = auth()->id();

        if ($userId) {
            return $userId;
        }

        return (int) (User::query()
            ->where('username', env('BENIGLOW_ADMIN_USERNAME', 'admin'))
            ->orWhere('email', env('BENIGLOW_ADMIN_EMAIL', 'admin@beniglow.com'))
            ->orderBy('id')
            ->value('id') ?: User::query()->orderBy('id')->value('id'));
    }
}
