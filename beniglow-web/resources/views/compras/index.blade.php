@extends('layouts.app')
@section('title', 'Compras')
@section('header', 'Historial de Compras')

@section('content')
@php $moneda = $empresaGlobal->moneda ?? 'S/'; @endphp

<div class="grid grid-cols-1 xl:grid-cols-[1.1fr_.9fr] gap-5 mb-5">
    <div class="bg-white rounded-2xl shadow-md p-6 overflow-hidden relative">
        <div class="absolute inset-y-0 right-0 w-1/3 bg-gradient-to-l from-[#fff1ea] to-transparent pointer-events-none"></div>
        <div class="relative">
            <p class="text-xs uppercase tracking-[0.24em] text-[#b7775b] font-semibold">Inventario</p>
            <h3 class="mt-2 text-2xl font-bold text-slate-800">Compras y reposición</h3>
            <p class="mt-2 text-sm text-slate-500 max-w-2xl">Registra ingresos de mercadería, actualiza stock y conserva trazabilidad por proveedor, factura y fecha.</p>
            <div class="mt-5">
                <a href="{{ route('compras.create') }}" class="gradient-primary text-white px-5 py-2.5 rounded-xl font-semibold inline-flex items-center gap-2 shadow-sm">
                    <i class="fas fa-plus"></i>Nueva compra
                </a>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-2xl shadow-md p-5">
            <p class="text-xs text-slate-500">Compras</p>
            <p class="mt-1 text-2xl font-bold text-slate-800">{{ $resumen['cantidad'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5">
            <p class="text-xs text-slate-500">Recibidas</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600">{{ $resumen['recibidas'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5">
            <p class="text-xs text-slate-500">Total filtrado</p>
            <p class="mt-1 text-2xl font-bold text-[#b7775b]">{{ $moneda }}{{ number_format($resumen['total'] ?? 0, 2) }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-md p-5 mb-5 no-print">
    <div class="flex flex-col md:flex-row gap-3 justify-between">
        <form method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-2">
            <select name="proveedor_id" class="px-3 py-2.5 border border-slate-300 rounded-lg">
                <option value="">Todos los proveedores</option>
                @foreach($proveedores as $p)
                    <option value="{{ $p->id }}" {{ request('proveedor_id') == $p->id ? 'selected' : '' }}>{{ $p->razon_social }}</option>
                @endforeach
            </select>
            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="px-3 py-2.5 border border-slate-300 rounded-lg">
            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="px-3 py-2.5 border border-slate-300 rounded-lg">
            <div class="md:col-span-3 flex gap-2 justify-end">
                <button class="bg-slate-800 text-white px-4 py-2.5 rounded-lg font-semibold"><i class="fas fa-filter mr-1"></i>Filtrar</button>
                <a href="{{ route('compras.index') }}" class="bg-slate-100 text-slate-700 px-4 py-2.5 rounded-lg font-semibold"><i class="fas fa-rotate-left mr-1"></i>Limpiar</a>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
            <tr>
                <th class="text-left py-3 px-4">N° Compra</th>
                <th class="text-left py-3 px-4">Fecha</th>
                <th class="text-left py-3 px-4">Proveedor</th>
                <th class="text-left py-3 px-4">Factura</th>
                <th class="text-right py-3 px-4">Total</th>
                <th class="text-center py-3 px-4">Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($compras as $c)
            <tr class="border-b border-slate-100 hover:bg-slate-50">
                <td class="py-3 px-4 font-mono text-sm">{{ $c->numero }}</td>
                <td class="py-3 px-4 text-sm">{{ $c->fecha_compra->format('d/m/Y') }}</td>
                <td class="py-3 px-4">{{ $c->proveedor->razon_social }}</td>
                <td class="py-3 px-4 text-sm">{{ $c->numero_factura ?: '—' }}</td>
                <td class="py-3 px-4 text-right font-bold text-emerald-600">{{ $moneda }}{{ number_format($c->total, 2) }}</td>
                <td class="py-3 px-4 text-center"><span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">{{ ucfirst($c->estado) }}</span></td>
                <td class="py-3 px-4 text-right"><a href="{{ route('compras.show', $c->id) }}" class="text-blue-600 hover:bg-blue-50 p-2 rounded"><i class="fas fa-eye"></i></a></td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center py-12 text-slate-400">Sin compras</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $compras->withQueryString()->links() }}</div>
</div>
@endsection
