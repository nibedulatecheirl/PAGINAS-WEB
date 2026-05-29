@extends('layouts.app')
@section('title', 'Reporte de vencimientos')
@section('header', 'Productos por vencer')

@section('content')
@php
    $empresa = $empresaGlobal->nombre_comercial ?? 'BeniGlow Store';
    $razon = $empresaGlobal->razon_social ?? 'Beniglow E.I.R.L.';
    $vencidos = $productos->filter(fn($p) => now()->diffInDays($p->fecha_vencimiento, false) < 0)->count();
    $criticos = $productos->filter(fn($p) => ($d = now()->diffInDays($p->fecha_vencimiento, false)) >= 0 && $d <= 7)->count();
    $proximos = $productos->filter(fn($p) => ($d = now()->diffInDays($p->fecha_vencimiento, false)) > 7 && $d <= 30)->count();
@endphp

<div class="report-shell">
    <div class="report-print-header">
        <div>
            <p class="report-print-title">Reporte de vencimientos</p>
            <p class="report-print-meta">{{ $empresa }} · {{ $razon }}</p>
        </div>
        <div class="text-right report-print-meta">
            <p>Productos con vencimiento: {{ $productos->count() }}</p>
            <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <section class="report-hero p-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-[#b7775b] font-semibold">Control sanitario</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-800">Productos por vencer</h2>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl">Seguimiento de lotes con fecha de vencimiento para priorizar revisión, rotación o retiro de stock.</p>
            </div>
            <button onclick="window.print()" type="button" class="no-print bg-slate-800 text-white px-5 py-2.5 rounded-lg font-semibold">
                <i class="fas fa-print mr-2"></i>Imprimir
            </button>
        </div>
    </section>

    <section class="report-kpi-grid">
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Con vencimiento</p>
            <p class="mt-2 text-2xl font-bold report-kpi-accent">{{ $productos->count() }}</p>
            <p class="mt-1 text-xs text-slate-500">Productos activos</p>
        </div>
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Vencidos</p>
            <p class="mt-2 text-2xl font-bold {{ $vencidos > 0 ? 'text-red-600' : 'text-slate-800' }}">{{ $vencidos }}</p>
            <p class="mt-1 text-xs text-slate-500">Revisión inmediata</p>
        </div>
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Críticos</p>
            <p class="mt-2 text-2xl font-bold {{ $criticos > 0 ? 'text-orange-600' : 'text-slate-800' }}">{{ $criticos }}</p>
            <p class="mt-1 text-xs text-slate-500">0 a 7 días</p>
        </div>
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Próximos</p>
            <p class="mt-2 text-2xl font-bold {{ $proximos > 0 ? 'text-yellow-700' : 'text-slate-800' }}">{{ $proximos }}</p>
            <p class="mt-1 text-xs text-slate-500">8 a 30 días</p>
        </div>
    </section>

    <section class="report-sheet p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
            <h3 class="font-bold text-slate-800">Detalle de vencimientos</h3>
            <span class="text-xs text-slate-500">Ordenado por fecha más cercana</span>
        </div>
        <div class="report-table-wrapper">
            <table class="report-table text-sm">
                <thead>
                    <tr>
                        <th class="text-left">Producto</th>
                        <th class="text-left">Código</th>
                        <th class="text-left">Categoría</th>
                        <th class="text-left">Lote</th>
                        <th class="text-right">Stock</th>
                        <th class="text-left">Vencimiento</th>
                        <th class="text-center">Días</th>
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($productos as $p)
                    @php
                        $diasFalta = (int) now()->diffInDays($p->fecha_vencimiento, false);
                        $estado = $diasFalta < 0 ? 'Vencido' : ($diasFalta <= 7 ? 'Crítico' : ($diasFalta <= 30 ? 'Próximo' : 'Vigente'));
                    @endphp
                    <tr>
                        <td class="font-semibold">{{ $p->nombre }}</td>
                        <td class="font-mono text-xs">{{ $p->codigo }}</td>
                        <td>{{ $p->categoria?->nombre ?? '—' }}</td>
                        <td class="font-mono text-xs">{{ $p->lote ?: '—' }}</td>
                        <td class="text-right font-bold">{{ number_format($p->stock, 2) }}</td>
                        <td>{{ $p->fecha_vencimiento->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $diasFalta }}</td>
                        <td class="text-center font-semibold">{{ $estado }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-slate-400">Sin productos con fecha de vencimiento</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
