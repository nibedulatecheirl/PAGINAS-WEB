@extends('layouts.app')
@section('title', 'Pedidos Web')
@section('header', 'Pedidos Web')

@section('content')
@php $moneda = $empresaGlobal->moneda ?? 'S/'; @endphp

<div class="bg-white rounded-2xl shadow-md p-5 mb-5">
    <div class="flex flex-col md:flex-row gap-3 justify-between">
        <form method="GET" class="flex flex-1 flex-wrap gap-2">
            <div class="relative flex-1 min-w-64">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input name="buscar" value="{{ request('buscar') }}" placeholder="Buscar pedido, cliente o referencia"
                       class="w-full pl-12 pr-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500">
            </div>
            <select name="estado" class="px-3 py-2.5 border border-slate-300 rounded-lg">
                <option value="">Todos los estados</option>
                @foreach(['pendiente_pago' => 'Pendiente pago', 'pagado' => 'Pagado', 'preparando' => 'Preparando', 'enviado' => 'Enviado', 'entregado' => 'Entregado', 'cancelado' => 'Cancelado'] as $key => $label)
                    <option value="{{ $key }}" {{ request('estado') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="estado_pago" class="px-3 py-2.5 border border-slate-300 rounded-lg">
                <option value="">Pago: todos</option>
                @foreach(['pendiente' => 'Pendiente', 'pagado' => 'Pagado', 'rechazado' => 'Rechazado', 'reembolsado' => 'Reembolsado'] as $key => $label)
                    <option value="{{ $key }}" {{ request('estado_pago') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button class="bg-slate-800 text-white px-4 py-2.5 rounded-lg hover:bg-slate-900"><i class="fas fa-filter"></i></button>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="text-left py-3 px-4">Pedido</th>
                    <th class="text-left py-3 px-4">Cliente</th>
                    <th class="text-center py-3 px-4">Estado</th>
                    <th class="text-right py-3 px-4">Total</th>
                    <th class="text-left py-3 px-4">Venta</th>
                    <th class="text-right py-3 px-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $pedido)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="py-3 px-4">
                            <p class="font-semibold text-slate-800">{{ $pedido->codigo }}</p>
                            <p class="text-xs text-slate-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                        </td>
                        <td class="py-3 px-4">
                            <p class="font-semibold text-slate-800">{{ $pedido->cliente_nombre }}</p>
                            <p class="text-xs text-slate-500">{{ $pedido->cliente_email ?: $pedido->cliente_telefono }}</p>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs">{{ str_replace('_', ' ', $pedido->estado) }}</span>
                            <span class="px-2 py-1 {{ $pedido->estado_pago === 'pagado' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }} rounded-full text-xs">{{ $pedido->estado_pago }}</span>
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-emerald-600">{{ $moneda }} {{ number_format($pedido->total, 2) }}</td>
                        <td class="py-3 px-4 text-sm text-slate-600">
                            {{ $pedido->venta?->numero_ticket ?: 'Sin venta generada' }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('pedidos-web.show', $pedido) }}" class="p-2 hover:bg-blue-50 text-blue-600 rounded-lg" title="Ver"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-slate-400">
                            <i class="fas fa-bag-shopping text-5xl mb-2"></i>
                            <p>No hay pedidos web registrados</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100">{{ $pedidos->links() }}</div>
</div>
@endsection
