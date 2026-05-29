@extends('layouts.app')
@section('title', 'Reporte de productos')
@section('header', 'Productos más vendidos')

@section('content')
@php
    $moneda = $empresaGlobal->moneda ?? 'S/';
    $empresa = $empresaGlobal->nombre_comercial ?? 'BeniGlow Store';
    $razon = $empresaGlobal->razon_social ?? 'Beniglow E.I.R.L.';
    $periodo = \Carbon\Carbon::parse($desde)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($hasta)->format('d/m/Y');
    $unidades = $productos->sum('cantidad_vendida');
    $ingresos = $productos->sum('total_ingresos');
    $top = $productos->first();
@endphp

<div class="report-shell">
    <div class="report-print-header">
        <div>
            <p class="report-print-title">Reporte de productos vendidos</p>
            <p class="report-print-meta">{{ $empresa }} · {{ $razon }}</p>
        </div>
        <div class="text-right report-print-meta">
            <p>Periodo: {{ $periodo }}</p>
            <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <section class="report-hero p-6">
        <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-5">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-[#b7775b] font-semibold">Rotación</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-800">Productos más vendidos</h2>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl">Ranking de productos vendidos entre {{ $periodo }} por unidades, precio promedio e ingresos generados.</p>
            </div>
            <form method="GET" class="no-print grid grid-cols-1 sm:grid-cols-[1fr_1fr_auto_auto] gap-3 xl:min-w-[620px]">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Desde</label>
                    <input type="date" name="desde" value="{{ $desde }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Hasta</label>
                    <input type="date" name="hasta" value="{{ $hasta }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <button class="self-end gradient-primary text-white px-5 py-2.5 rounded-lg font-semibold">
                    <i class="fas fa-search mr-2"></i>Generar
                </button>
                <button onclick="window.print()" type="button" class="self-end bg-slate-800 text-white px-5 py-2.5 rounded-lg font-semibold">
                    <i class="fas fa-print mr-2"></i>Imprimir
                </button>
            </form>
        </div>
    </section>

    <section class="report-kpi-grid">
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Productos vendidos</p>
            <p class="mt-2 text-2xl font-bold report-kpi-accent">{{ $productos->count() }}</p>
            <p class="mt-1 text-xs text-slate-500">Referencias con venta</p>
        </div>
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Unidades vendidas</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ number_format($unidades, 2) }}</p>
            <p class="mt-1 text-xs text-slate-500">Total acumulado</p>
        </div>
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Ingresos por productos</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $moneda }}{{ number_format($ingresos, 2) }}</p>
            <p class="mt-1 text-xs text-slate-500">{{ $top ? 'Top: ' . $top->nombre : 'Sin ranking todavía' }}</p>
        </div>
    </section>

    <section class="report-sheet p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
            <h3 class="font-bold text-slate-800">Ranking de productos</h3>
            <span class="text-xs text-slate-500">Periodo {{ $periodo }}</span>
        </div>
        <div class="report-table-wrapper">
            <table class="report-table text-sm">
                <thead>
                    <tr>
                        <th class="text-left">#</th>
                        <th class="text-left">Código</th>
                        <th class="text-left">Producto</th>
                        <th class="text-left">Categoría</th>
                        <th class="text-right">Cantidad</th>
                        <th class="text-right">Precio prom.</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($productos as $i => $p)
                    <tr>
                        <td class="font-bold">{{ $i + 1 }}</td>
                        <td class="font-mono text-xs">{{ $p->codigo }}</td>
                        <td class="font-semibold">{{ $p->nombre }}</td>
                        <td class="text-slate-600">{{ $p->categoria ?: '—' }}</td>
                        <td class="text-right">{{ number_format($p->cantidad_vendida, 2) }}</td>
                        <td class="text-right">{{ $moneda }}{{ number_format($p->precio_promedio, 2) }}</td>
                        <td class="text-right font-bold">{{ $moneda }}{{ number_format($p->total_ingresos, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-slate-400">Sin ventas en el periodo seleccionado</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
