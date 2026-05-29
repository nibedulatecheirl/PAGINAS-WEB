<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\TurnoCaja;
use App\Models\MovimientoInventario;
use App\Models\Empresa;
use App\Models\PuntoFidelidad;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::with(['cliente', 'user']);

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where('numero_ticket', 'LIKE', "%$b%");
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_venta', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_venta', '<=', $request->fecha_hasta);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('canal')) {
            $query->where('canal', $request->canal);
        }

        $resumenVentas = (clone $query)->get(['total', 'canal', 'estado']);
        $resumen = [
            'cantidad' => $resumenVentas->count(),
            'total' => $resumenVentas->sum('total'),
            'web' => $resumenVentas->where('canal', 'web')->count(),
            'mostrador' => $resumenVentas->where('canal', 'pos')->count(),
            'anuladas' => $resumenVentas->where('estado', 'anulada')->count(),
        ];

        $ventas = $query->orderByDesc('fecha_venta')->paginate(20);
        return view('ventas.index', compact('ventas', 'resumen'));
    }

    public function pos()
    {
        $turnoActivo = TurnoCaja::where('user_id', auth()->id())
            ->where('estado', 'abierto')->first();

        if (!$turnoActivo) {
            return redirect()->route('caja.index')
                ->with('warning', 'Debe abrir un turno de caja antes de realizar ventas');
        }

        $productos = Producto::with('categoria')
            ->where('activo', true)
            ->where('destacado', true)
            ->limit(12)->get();

        $categorias = \App\Models\Categoria::where('activo', true)->orderBy('nombre')->get();
        $clientes = Cliente::where('activo', true)->orderBy('nombres')->limit(50)->get();

        return view('ventas.pos', compact('productos', 'categorias', 'clientes', 'turnoActivo'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'forma_pago' => 'required|string',
            'monto_recibido' => 'required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|numeric|min:0.001',
            'items.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $turnoActivo = TurnoCaja::where('user_id', auth()->id())
            ->where('estado', 'abierto')->first();

        if (!$turnoActivo) {
            return response()->json(['error' => 'No hay turno de caja abierto'], 400);
        }

        $empresa = Empresa::first();
        $tasaImpuesto = $empresa ? $empresa->impuesto / 100 : 0;
        $impuestoIncluido = $empresa ? $empresa->impuesto_incluido : true;

        DB::beginTransaction();
        try {
            $productoIds = collect($data['items'])->pluck('producto_id')->unique()->values();
            $productos = Producto::whereIn('id', $productoIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach (collect($data['items'])->groupBy('producto_id') as $productoId => $itemsProducto) {
                $producto = $productos->get((int) $productoId);
                $cantidadTotal = $itemsProducto->sum(fn ($item) => (float) $item['cantidad']);

                if (! $producto || ! $producto->activo) {
                    throw ValidationException::withMessages([
                        'items' => 'Uno de los productos ya no esta activo.',
                    ]);
                }

                if ($producto->controla_stock && $producto->stock < $cantidadTotal) {
                    throw ValidationException::withMessages([
                        'items' => "Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock}.",
                    ]);
                }
            }

            $subtotal = 0;
            $impuestoTotal = 0;

            foreach ($data['items'] as $item) {
                $producto = $productos->get((int) $item['producto_id']);
                $itemSubtotal = $item['cantidad'] * $item['precio_unitario'];
                $subtotal += $itemSubtotal;

                if ($producto->aplica_impuesto && !$impuestoIncluido) {
                    $impuestoTotal += $itemSubtotal * $tasaImpuesto;
                }
            }

            $descuento = $data['descuento'] ?? 0;
            $total = $subtotal - $descuento + ($impuestoIncluido ? 0 : $impuestoTotal);

            if ($impuestoIncluido) {
                $impuestoTotal = $total - ($total / (1 + $tasaImpuesto));
            }

            $cambio = $data['monto_recibido'] - $total;
            if ($cambio < 0) $cambio = 0;

            // Generar número de ticket
            $ultimoTicket = Venta::orderByDesc('id')->first();
            $numero = $ultimoTicket ? ((int) substr($ultimoTicket->numero_ticket, -8)) + 1 : 1;
            $numeroTicket = 'T001-' . str_pad($numero, 8, '0', STR_PAD_LEFT);

            $venta = Venta::create([
                'numero_ticket' => $numeroTicket,
                'tipo_comprobante' => 'TICKET',
                'serie' => 'T001',
                'canal' => 'pos',
                'fecha_venta' => now(),
                'cliente_id' => $data['cliente_id'] ?? null,
                'user_id' => auth()->id(),
                'turno_caja_id' => $turnoActivo->id,
                'subtotal' => $subtotal - $impuestoTotal,
                'descuento' => $descuento,
                'impuesto' => $impuestoTotal,
                'total' => $total,
                'monto_recibido' => $data['monto_recibido'],
                'cambio' => $cambio,
                'forma_pago' => $data['forma_pago'],
                'estado_pago' => 'pagado',
                'estado' => 'completada',
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $producto = $productos->get((int) $item['producto_id']);
                $itemSubtotal = $item['cantidad'] * $item['precio_unitario'];
                $itemImpuesto = ($producto->aplica_impuesto && !$impuestoIncluido)
                    ? $itemSubtotal * $tasaImpuesto : 0;

                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'codigo' => $producto->codigo,
                    'descripcion' => $producto->nombre,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'descuento' => 0,
                    'impuesto' => $itemImpuesto,
                    'subtotal' => $itemSubtotal,
                    'total' => $itemSubtotal + $itemImpuesto,
                ]);

                if ($producto->controla_stock) {
                    $stockAnterior = $producto->stock;
                    $stockNuevo = $stockAnterior - $item['cantidad'];
                    $producto->update(['stock' => $stockNuevo]);
                    $productos->put($producto->id, $producto->fresh());

                    MovimientoInventario::create([
                        'producto_id' => $producto->id,
                        'user_id' => auth()->id(),
                        'tipo' => 'salida',
                        'motivo' => 'Venta #' . $numeroTicket,
                        'cantidad' => $item['cantidad'],
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $stockNuevo,
                        'referencia_tipo' => 'venta',
                        'referencia_id' => $venta->id,
                        'fecha' => now(),
                    ]);
                }
            }

            // Actualizar totales del turno
            $turnoActivo->increment('total_ventas', $total);
            $turnoActivo->increment('cantidad_ventas');
            if ($data['forma_pago'] === 'efectivo') {
                $turnoActivo->increment('total_efectivo', $total);
            } elseif ($data['forma_pago'] === 'tarjeta') {
                $turnoActivo->increment('total_tarjeta', $total);
            } else {
                $turnoActivo->increment('total_otros', $total);
            }

            // Puntos de fidelidad (1 punto por cada $10)
            if ($data['cliente_id']) {
                $cliente = Cliente::find($data['cliente_id']);
                $puntos = floor($total / 10);
                if ($puntos > 0) {
                    $saldoAnterior = $cliente->puntos_fidelidad;
                    $cliente->increment('puntos_fidelidad', $puntos);
                    PuntoFidelidad::create([
                        'cliente_id' => $cliente->id,
                        'venta_id' => $venta->id,
                        'tipo' => 'ganado',
                        'puntos' => $puntos,
                        'saldo_anterior' => $saldoAnterior,
                        'saldo_nuevo' => $saldoAnterior + $puntos,
                        'descripcion' => 'Venta ' . $numeroTicket,
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'venta_id' => $venta->id,
                'numero_ticket' => $numeroTicket,
                'redirect' => route('ventas.ticket', $venta->id),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            return response()->json(['error' => 'Error al procesar venta: ' . $e->getMessage()], 500);
        }
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'user', 'detalles.producto']);
        return view('ventas.show', compact('venta'));
    }

    public function ticket(Venta $venta)
    {
        $venta->load(['cliente', 'user', 'detalles']);
        return view('ventas.ticket', compact('venta'));
    }

    public function anular(Venta $venta)
    {
        if ($venta->estado === 'anulada') {
            return back()->with('error', 'La venta ya está anulada');
        }

        DB::beginTransaction();
        try {
            // Devolver stock
            foreach ($venta->detalles as $detalle) {
                $producto = $detalle->producto;
                if ($producto && $producto->controla_stock) {
                    $stockAnterior = $producto->stock;
                    $stockNuevo = $stockAnterior + $detalle->cantidad;
                    $producto->update(['stock' => $stockNuevo]);

                    MovimientoInventario::create([
                        'producto_id' => $producto->id,
                        'user_id' => auth()->id(),
                        'tipo' => 'entrada',
                        'motivo' => 'Anulación venta #' . $venta->numero_ticket,
                        'cantidad' => $detalle->cantidad,
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $stockNuevo,
                        'referencia_tipo' => 'venta_anulada',
                        'referencia_id' => $venta->id,
                        'fecha' => now(),
                    ]);
                }
            }

            $venta->update(['estado' => 'anulada']);
            DB::commit();

            return back()->with('success', 'Venta anulada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al anular: ' . $e->getMessage());
        }
    }
}
