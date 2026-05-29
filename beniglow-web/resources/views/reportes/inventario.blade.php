@extends('layouts.app')
@section('title', 'Reporte de inventario')
@section('header', 'Estado del inventario')

@section('content')
@php
    $moneda = $empresaGlobal->moneda ?? 'S/';
    $empresa = $empresaGlobal->nombre_comercial ?? 'BeniGlow Store';
    $razon = $empresaGlobal->razon_social ?? 'Beniglow E.I.R.L.';
    $stockBajo = $productos->filter(fn($p) => $p->stock_bajo)->count();
    $agotados = $productos->filter(fn($p) => (float) $p->stock <= 0)->count();
@endphp

<div class="report-shell">
    <div class="report-print-header">
        <div>
            <p class="report-print-title">Reporte de inventario</p>
            <p class="report-print-meta">{{ $empresa }} · {{ $razon }}</p>
        </div>
        <div class="text-right report-print-meta">
            <p>Productos activos: {{ $productos->count() }}</p>
            <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <section class="report-hero p-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-[#b7775b] font-semibold">Inventario</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-800">Estado de stock y valorización</h2>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl">Vista consolidada de productos activos, stock disponible, mínimos, valorización de compra y potencial de venta.</p>
            </div>
            <button onclick="window.print()" type="button" class="no-print bg-slate-800 text-white px-5 py-2.5 rounded-lg font-semibold">
                <i class="fas fa-print mr-2"></i>Imprimir
            </button>
        </div>
    </section>

    <section class="report-kpi-grid">
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Productos activos</p>
            <p class="mt-2 text-2xl font-bold report-kpi-accent">{{ $productos->count() }}</p>
            <p class="mt-1 text-xs text-slate-500">Catálogo administrable</p>
        </div>
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Stock bajo</p>
            <p class="mt-2 text-2xl font-bold {{ $stockBajo > 0 ? 'text-red-600' : 'text-slate-800' }}">{{ $stockBajo }}</p>
            <p class="mt-1 text-xs text-slate-500">{{ $agotados }} agotados</p>
        </div>
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Valor de compra</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $moneda }}{{ number_format($valorTotal, 2) }}</p>
            <p class="mt-1 text-xs text-slate-500">Costo del stock</p>
        </div>
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Valor potencial</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $moneda }}{{ number_format($valorVenta, 2) }}</p>
            <p class="mt-1 text-xs text-slate-500">Margen bruto: {{ $moneda }}{{ number_format($valorVenta - $valorTotal, 2) }}</p>
        </div>
    </section>

    <section class="report-sheet p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
            <h3 class="font-bold text-slate-800">Detalle de inventario</h3>
            <span class="text-xs text-slate-500">Generado el {{ now()->format('d/m/Y H:i') }}</span>
        </div>
        <div class="report-table-wrapper">
            <table class="report-table text-sm">
                <thead>
                    <tr>
                        <th class="text-left">Código</th>
                        <th class="text-left">Producto</th>
                        <th class="text-left">Categoría</th>
                        <th class="text-left">Proveedor</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Mín.</th>
                        <th class="text-right">P. compra</th>
                        <th class="text-right">P. venta</th>
                        <th class="text-right">Valor stock</th>
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($productos as $p)
                    @php
                        $sinStock = (float) $p->stock <= 0;
                        $estado = $sinStock ? 'Agotado' : ($p->stock_bajo ? 'Bajo' : 'OK');
                    @endphp
                    <tr>
                        <td class="font-mono text-xs">{{ $p->codigo }}</td>
                        <td class="font-semibold">{{ $p->nombre }}</td>
                        <td>{{ $p->categoria?->nombre ?? '—' }}</td>
                        <td>{{ $p->proveedor?->nombre_comercial ?? $p->proveedor?->razon_social ?? '—' }}</td>
                        <td class="text-right font-bold {{ $p->stock_bajo ? 'text-red-600' : '' }}">{{ number_format($p->stock, 2) }}</td>
                        <td class="text-right">{{ number_format($p->stock_minimo, 2) }}</td>
                        <td class="text-right">{{ $moneda }}{{ number_format($p->precio_compra, 2) }}</td>
                        <td class="text-right">{{ $moneda }}{{ number_format($p->precio_venta, 2) }}</td>
                        <td class="text-right font-semibold">{{ $moneda }}{{ number_format($p->stock * $p->precio_compra, 2) }}</td>
                        <td class="text-center font-semibold">{{ $estado }}</td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="text-center text-slate-400">Sin productos activos para mostrar</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
