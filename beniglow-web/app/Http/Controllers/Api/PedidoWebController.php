<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PedidoWeb;
use App\Services\PedidoWebService;
use Illuminate\Http\Request;

class PedidoWebController extends Controller
{
    public function store(Request $request, PedidoWebService $service)
    {
        $data = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'cliente' => 'required_without:cliente_id|array',
            'cliente.nombres' => 'required_without:cliente_id|string|max:255',
            'cliente.apellidos' => 'nullable|string|max:255',
            'cliente.tipo_documento' => 'nullable|string|max:20',
            'cliente.documento' => 'nullable|string|max:30',
            'cliente.email' => 'nullable|email|max:255',
            'cliente.telefono' => 'required_without:cliente_id|string|max:30',
            'direccion_envio' => 'nullable|array',
            'direccion_envio.direccion' => 'nullable|string|max:255',
            'direccion_envio.ciudad' => 'nullable|string|max:120',
            'direccion_envio.referencia' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'nullable|integer|exists:productos,id',
            'items.*.slug' => 'nullable|string|exists:productos,slug',
            'items.*.cantidad' => 'required|numeric|min:0.001|max:1000',
            'descuento' => 'nullable|numeric|min:0',
            'envio' => 'nullable|numeric|min:0',
            'metodo_pago' => 'required|string|max:50',
            'referencia_pago' => 'nullable|string|max:255',
            'payment_payload' => 'nullable|array',
            'estado_pago' => 'nullable|in:pendiente,pagado',
            'confirmar_pago' => 'nullable|boolean',
            'origen' => 'nullable|string|max:255',
            'notas' => 'nullable|string|max:1000',
        ]);

        foreach ($data['items'] as $index => $item) {
            if (empty($item['producto_id']) && empty($item['slug'])) {
                return response()->json([
                    'message' => 'Cada item debe enviar producto_id o slug.',
                    'errors' => ["items.{$index}" => ['Cada item debe enviar producto_id o slug.']],
                ], 422);
            }
        }

        $pedido = $service->crearDesdePayload($data);

        return response()->json([
            'message' => 'Pedido web registrado correctamente.',
            'data' => $this->pedidoResource($pedido),
        ], 201);
    }

    public function confirmarPago(PedidoWeb $pedidoWeb, Request $request, PedidoWebService $service)
    {
        $data = $request->validate([
            'metodo_pago' => 'nullable|string|max:50',
            'referencia_pago' => 'nullable|string|max:255',
            'payment_payload' => 'nullable|array',
        ]);

        $pedido = $service->confirmarPago($pedidoWeb, $data);

        return response()->json([
            'message' => 'Pago confirmado y stock descontado.',
            'data' => $this->pedidoResource($pedido),
        ]);
    }

    private function pedidoResource(PedidoWeb $pedido): array
    {
        $pedido->loadMissing('detalles.producto', 'venta');

        return [
            'codigo' => $pedido->codigo,
            'estado' => $pedido->estado,
            'estado_pago' => $pedido->estado_pago,
            'estado_stock' => $pedido->estado_stock,
            'subtotal' => (float) $pedido->subtotal,
            'descuento' => (float) $pedido->descuento,
            'impuesto' => (float) $pedido->impuesto,
            'envio' => (float) $pedido->envio,
            'total' => (float) $pedido->total,
            'moneda' => $pedido->moneda,
            'cliente' => [
                'nombre' => $pedido->cliente_nombre,
                'email' => $pedido->cliente_email,
                'telefono' => $pedido->cliente_telefono,
                'documento' => $pedido->cliente_documento,
            ],
            'items' => $pedido->detalles->map(fn ($detalle) => [
                'producto_id' => $detalle->producto_id,
                'slug' => $detalle->producto?->slug,
                'nombre' => $detalle->nombre,
                'cantidad' => (float) $detalle->cantidad,
                'precio_unitario' => (float) $detalle->precio_unitario,
                'subtotal' => (float) $detalle->subtotal,
                'impuesto' => (float) $detalle->impuesto,
                'total' => (float) $detalle->total,
            ])->values(),
            'venta_id' => $pedido->venta_id,
            'numero_venta' => $pedido->venta?->numero_ticket,
            'created_at' => $pedido->created_at?->toISOString(),
            'confirmed_at' => $pedido->confirmed_at?->toISOString(),
        ];
    }
}
