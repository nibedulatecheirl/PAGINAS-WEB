<?php

namespace App\Http\Controllers;

use App\Models\PedidoWeb;
use App\Services\PedidoWebService;
use Illuminate\Http\Request;

class PedidoWebController extends Controller
{
    public function index(Request $request)
    {
        $query = PedidoWeb::with('cliente', 'venta');

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($subquery) use ($buscar) {
                $subquery->where('codigo', 'like', "%{$buscar}%")
                    ->orWhere('cliente_nombre', 'like', "%{$buscar}%")
                    ->orWhere('cliente_email', 'like', "%{$buscar}%")
                    ->orWhere('cliente_telefono', 'like', "%{$buscar}%")
                    ->orWhere('referencia_pago', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('estado_pago')) {
            $query->where('estado_pago', $request->estado_pago);
        }

        $pedidos = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('pedidos_web.index', compact('pedidos'));
    }

    public function show(PedidoWeb $pedidoWeb)
    {
        $pedidoWeb->load('detalles.producto', 'cliente', 'venta');

        return view('pedidos_web.show', compact('pedidoWeb'));
    }

    public function confirmarPago(PedidoWeb $pedidoWeb, Request $request, PedidoWebService $service)
    {
        $request->validate([
            'metodo_pago' => 'required|string|max:50',
            'referencia_pago' => 'nullable|string|max:255',
        ]);

        $service->confirmarPago($pedidoWeb, $request->only('metodo_pago', 'referencia_pago'));

        return redirect()
            ->route('pedidos-web.show', $pedidoWeb)
            ->with('success', 'Pago confirmado, venta generada y stock descontado.');
    }

    public function actualizarEstado(PedidoWeb $pedidoWeb, Request $request)
    {
        $data = $request->validate([
            'estado' => 'required|in:preparando,enviado,entregado,cancelado',
        ]);

        $estado = $data['estado'];

        if ($estado !== 'cancelado' && $pedidoWeb->estado_pago !== 'pagado') {
            return back()->with('error', 'Primero confirma el pago para avanzar el pedido.');
        }

        if ($estado === 'cancelado' && $pedidoWeb->estado_stock === 'descontado') {
            return back()->with('error', 'Este pedido ya descontó stock. Anula la venta relacionada para revertirlo correctamente.');
        }

        $pedidoWeb->update([
            'estado' => $estado,
            'cancelled_at' => $estado === 'cancelado' ? now() : $pedidoWeb->cancelled_at,
        ]);

        if ($pedidoWeb->venta && in_array($estado, ['preparando', 'enviado', 'entregado'], true)) {
            $pedidoWeb->venta->update(['estado_envio' => $estado]);
        }

        return back()->with('success', 'Estado del pedido actualizado correctamente.');
    }
}
