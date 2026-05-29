@extends('layouts.app')
@section('title', 'Ventas')
@section('header', 'Historial de Ventas')

@section('content')
@php $moneda = $empresaGlobal->moneda ?? 'S/'; @endphp

<div class="grid grid-cols-1 xl:grid-cols-[1.2fr_.8fr] gap-5 mb-5">
    <div class="bg-white rounded-2xl shadow-md p-6 overflow-hidden relative">
        <div class="absolute inset-y-0 right-0 w-1/3 bg-gradient-to-l from-[#fff1ea] to-transparent pointer-events-none"></div>
        <div class="relative">
            <p class="text-xs uppercase tracking-[0.24em] text-[#b7775b] font-semibold">Operaciones</p>
            <h3 class="mt-2 text-2xl font-bold text-slate-800">Ventas registradas</h3>
            <p class="mt-2 text-sm text-slate-500 max-w-2xl">Controla ventas de mostrador y pedidos web convertidos en venta. Usa los filtros para revisar tickets, canales y estados.</p>
            <div class="mt-5 flex flex-wrap gap-2">
                <a href="{{ route('ventas.pos') }}" class="gradient-primary text-white px-5 py-2.5 rounded-xl font-semibold inline-flex items-center gap-2 shadow-sm">
                    <i class="fas fa-plus"></i>Nueva venta
                </a>
                <a href="{{ route('pedidos-web.index') }}" class="bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl font-semibold inline-flex items-center gap-2 hover:bg-slate-200">
                    <i class="fas fa-bag-shopping"></i>Pedidos web
                </a>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white rounded-2xl shadow-md p-5">
            <p class="text-xs text-slate-500">Ventas filtradas</p>
            <p class="mt-1 text-2xl font-bold text-slate-800">{{ $resumen['cantidad'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5">
            <p class="text-xs text-slate-500">Total filtrado</p>
            <p class="mt-1 text-2xl font-bold text-[#b7775b]">{{ $moneda }}{{ number_format($resumen['total'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5">
            <p class="text-xs text-slate-500">Canal web</p>
            <p class="mt-1 text-2xl font-bold text-blue-600">{{ $resumen['web'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5">
            <p class="text-xs text-slate-500">Mostrador</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600">{{ $resumen['mostrador'] ?? 0 }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-md p-5 mb-5 no-print">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3">
        <input name="buscar" value="{{ request('buscar') }}" placeholder="N° Ticket" class="px-3 py-2.5 border border-slate-300 rounded-lg">
        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="px-3 py-2.5 border border-slate-300 rounded-lg">
        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="px-3 py-2.5 border border-slate-300 rounded-lg">
        <select name="estado" class="px-3 py-2.5 border border-slate-300 rounded-lg">
            <option value="">Todos los estados</option>
            <option value="completada" {{ request('estado')=='completada'?'selected':'' }}>Completadas</option>
            <option value="anulada" {{ request('estado')=='anulada'?'selected':'' }}>Anuladas</option>
        </select>
        <select name="canal" class="px-3 py-2.5 border border-slate-300 rounded-lg">
            <option value="">Todos los canales</option>
            <option value="web" {{ request('canal')=='web'?'selected':'' }}>Web</option>
            <option value="pos" {{ request('canal')=='pos'?'selected':'' }}>Mostrador</option>
        </select>
        <div class="flex gap-2">
            <button class="flex-1 bg-slate-800 text-white px-4 py-2.5 rounded-lg"><i class="fas fa-filter mr-1"></i>Filtrar</button>
            <a href="{{ route('ventas.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2.5 rounded-lg flex items-center justify-center" title="Limpiar filtros"><i class="fas fa-rotate-left"></i></a>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
            <tr>
                <th class="text-left py-3 px-4">Ticket</th>
                <th class="text-left py-3 px-4">Fecha</th>
                <th class="text-left py-3 px-4">Cliente</th>
                <th class="text-left py-3 px-4">Cajero</th>
                <th class="text-left py-3 px-4">Pago</th>
                <th class="text-right py-3 px-4">Total</th>
                <th class="text-center py-3 px-4">Estado</th>
                <th class="text-right py-3 px-4">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($ventas as $v)
            <tr class="border-b border-slate-100 hover:bg-slate-50">
                <td class="py-3 px-4">
                    <p class="font-mono text-sm font-semibold">{{ $v->numero_ticket }}</p>
                    <span class="text-xs {{ $v->canal === 'web' ? 'text-blue-600' : 'text-slate-500' }}">{{ $v->canal === 'web' ? 'Web' : 'Mostrador' }}</span>
                </td>
                <td class="py-3 px-4 text-sm">{{ $v->fecha_venta->format('d/m/Y H:i') }}</td>
                <td class="py-3 px-4 text-sm">{{ $v->cliente?->nombre_completo ?? 'Genérico' }}</td>
                <td class="py-3 px-4 text-sm">{{ $v->user->name }}</td>
                <td class="py-3 px-4"><span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-full text-xs">{{ ucfirst($v->forma_pago) }}</span></td>
                <td class="py-3 px-4 text-right font-bold text-emerald-600">{{ $moneda }}{{ number_format($v->total, 2) }}</td>
                <td class="py-3 px-4 text-center">
                    @if($v->estado == 'completada')
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Completada</span>
                    @else
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">Anulada</span>
                    @endif
                </td>
                <td class="py-3 px-4 text-right">
                    <a href="{{ route('ventas.show', $v->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('ventas.ticket', $v->id) }}" target="_blank" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded"><i class="fas fa-print"></i></a>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" class="text-center py-12 text-slate-400">Sin ventas</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $ventas->withQueryString()->links() }}</div>
</div>
@endsection
