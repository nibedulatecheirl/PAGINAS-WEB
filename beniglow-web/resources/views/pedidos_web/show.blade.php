@extends('layouts.app')
@section('title', $pedidoWeb->codigo)
@section('header', 'Pedido web: ' . $pedidoWeb->codigo)

@section('content')
@php
    $moneda = $empresaGlobal->moneda ?? 'S/';
    $metodoActual = old('metodo_pago', $pedidoWeb->metodo_pago ?: 'web');
    $metodosPago = [
        'web' => 'Pago web',
        'yape' => 'Yape',
        'plin' => 'Plin',
        'transferencia' => 'Transferencia bancaria',
        'tarjeta' => 'Tarjeta',
        'efectivo_entrega' => 'Efectivo contra entrega',
    ];
    $estadosPedido = [
        'pendiente_pago' => 'Pendiente de pago',
        'preparando' => 'Preparando',
        'enviado' => 'Enviado',
        'entregado' => 'Entregado',
        'cancelado' => 'Cancelado',
    ];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-5">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">{{ $pedidoWeb->codigo }}</h3>
                    <p class="text-sm text-slate-500">{{ $pedidoWeb->created_at->format('d/m/Y H:i') }} &middot; Origen: {{ $pedidoWeb->origen ?: 'web' }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm">{{ $estadosPedido[$pedidoWeb->estado] ?? str_replace('_', ' ', $pedidoWeb->estado) }}</span>
                    <span class="px-3 py-1 {{ $pedidoWeb->estado_pago === 'pagado' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }} rounded-full text-sm">Pago {{ $pedidoWeb->estado_pago }}</span>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-sm">Stock {{ str_replace('_', ' ', $pedidoWeb->estado_stock) }}</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs text-slate-500 uppercase border-b">
                        <tr>
                            <th class="text-left py-2">Producto</th>
                            <th class="text-right py-2">Cantidad</th>
                            <th class="text-right py-2">Precio</th>
                            <th class="text-right py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidoWeb->detalles as $detalle)
                            <tr class="border-b border-slate-100">
                                <td class="py-3">
                                    <p class="font-semibold text-slate-800">{{ $detalle->nombre }}</p>
                                    <p class="text-xs text-slate-500">{{ $detalle->codigo }} @if($detalle->producto?->marca) &middot; {{ $detalle->producto->marca }} @endif</p>
                                </td>
                                <td class="py-3 text-right">{{ number_format($detalle->cantidad, 2) }}</td>
                                <td class="py-3 text-right">{{ $moneda }} {{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td class="py-3 text-right font-semibold">{{ $moneda }} {{ number_format($detalle->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4">Cliente y entrega</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><p class="text-slate-500">Cliente</p><p class="font-semibold">{{ $pedidoWeb->cliente_nombre }}</p></div>
                <div><p class="text-slate-500">Documento</p><p class="font-semibold">{{ $pedidoWeb->cliente_documento ?: '-' }}</p></div>
                <div><p class="text-slate-500">Email</p><p class="font-semibold">{{ $pedidoWeb->cliente_email ?: '-' }}</p></div>
                <div><p class="text-slate-500">Teléfono</p><p class="font-semibold">{{ $pedidoWeb->cliente_telefono ?: '-' }}</p></div>
                <div class="md:col-span-2"><p class="text-slate-500">Dirección</p><p class="font-semibold">{{ data_get($pedidoWeb->direccion_envio, 'direccion', '-') }}</p></div>
                <div><p class="text-slate-500">Ciudad</p><p class="font-semibold">{{ data_get($pedidoWeb->direccion_envio, 'ciudad', '-') }}</p></div>
                <div><p class="text-slate-500">Referencia</p><p class="font-semibold">{{ data_get($pedidoWeb->direccion_envio, 'referencia', '-') }}</p></div>
                @if($pedidoWeb->notas)
                    <div class="md:col-span-2"><p class="text-slate-500">Notas</p><p class="font-semibold">{{ $pedidoWeb->notas }}</p></div>
                @endif
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4">Resumen</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Subtotal</span><span>{{ $moneda }} {{ number_format($pedidoWeb->subtotal, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Impuesto</span><span>{{ $moneda }} {{ number_format($pedidoWeb->impuesto, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Descuento</span><span>{{ $moneda }} {{ number_format($pedidoWeb->descuento, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Envío</span><span>{{ $moneda }} {{ number_format($pedidoWeb->envio, 2) }}</span></div>
                <div class="flex justify-between pt-3 border-t text-lg font-bold text-emerald-700"><span>Total</span><span>{{ $moneda }} {{ number_format($pedidoWeb->total, 2) }}</span></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4">Pago</h3>
            <p class="text-sm text-slate-500">Método</p>
            <p class="font-semibold mb-3">{{ $metodosPago[$pedidoWeb->metodo_pago] ?? ($pedidoWeb->metodo_pago ?: '-') }}</p>
            <p class="text-sm text-slate-500">Referencia</p>
            <p class="font-semibold mb-4">{{ $pedidoWeb->referencia_pago ?: '-' }}</p>

            @if($pedidoWeb->estado_pago !== 'pagado')
                <form method="POST" action="{{ route('pedidos-web.confirmar-pago', $pedidoWeb) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Método de pago <span class="text-red-500">*</span></label>
                        <select name="metodo_pago" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                            @foreach($metodosPago as $valor => $label)
                                <option value="{{ $valor }}" {{ $metodoActual === $valor ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('metodo_pago')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Referencia de pago</label>
                        <input type="text" name="referencia_pago" value="{{ old('referencia_pago', $pedidoWeb->referencia_pago) }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg" placeholder="Operación, voucher o nota interna">
                        @error('referencia_pago')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-lg font-semibold">
                        <i class="fas fa-check mr-1"></i>Confirmar pago y descontar stock
                    </button>
                </form>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4">Flujo del pedido</h3>
            <div class="space-y-3 text-sm text-slate-600">
                <div class="flex gap-3">
                    <span class="w-8 h-8 rounded-full bg-yellow-50 text-yellow-700 grid place-items-center flex-none"><i class="fas fa-hourglass-half"></i></span>
                    <p><strong>Pendiente de pago:</strong> el cliente registró el pedido desde la web. Todavía no descuenta stock ni genera venta.</p>
                </div>
                <div class="flex gap-3">
                    <span class="w-8 h-8 rounded-full bg-green-50 text-green-700 grid place-items-center flex-none"><i class="fas fa-check"></i></span>
                    <p><strong>Pagado y preparando:</strong> al confirmar pago se genera la venta, se descuenta stock y el pedido queda listo para alistado.</p>
                </div>
                <div class="flex gap-3">
                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-700 grid place-items-center flex-none"><i class="fas fa-truck"></i></span>
                    <p><strong>Enviado o entregado:</strong> se actualiza manualmente cuando el pedido sale o cuando el cliente lo recibe. Entregado es el cierre operativo.</p>
                </div>
            </div>
        </div>

        @if($pedidoWeb->venta)
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="font-bold text-slate-800 mb-4">Venta generada</h3>
                <p class="font-semibold">{{ $pedidoWeb->venta->numero_ticket }}</p>
                <a href="{{ route('ventas.show', $pedidoWeb->venta) }}" class="inline-flex mt-3 text-blue-600 hover:text-blue-700 font-semibold text-sm">Ver venta</a>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4">Estado operativo</h3>
            @if($pedidoWeb->estado_pago === 'pagado' && $pedidoWeb->estado !== 'cancelado')
                <form method="POST" action="{{ route('pedidos-web.actualizar-estado', $pedidoWeb) }}" class="space-y-3">
                    @csrf
                    <select name="estado" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                        @foreach(['preparando' => 'Preparando', 'enviado' => 'Enviado', 'entregado' => 'Entregado'] as $valor => $label)
                            <option value="{{ $valor }}" {{ $pedidoWeb->estado === $valor ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="w-full gradient-primary text-white py-2.5 rounded-lg font-semibold">
                        <i class="fas fa-rotate mr-1"></i>Actualizar estado
                    </button>
                </form>
            @elseif($pedidoWeb->estado === 'cancelado')
                <p class="text-sm text-slate-500">Este pedido fue cancelado.</p>
            @else
                <p class="text-sm text-slate-500">Confirma el pago para habilitar preparación, envío y entrega.</p>
            @endif
        </div>

        <a href="{{ route('pedidos-web.index') }}" class="block text-center bg-white shadow-md py-3 rounded-2xl text-slate-600 hover:bg-slate-50">Volver</a>
    </div>
</div>
@endsection
