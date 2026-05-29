@extends('layouts.app')
@section('title', 'Reportes')
@section('header', 'Centro de reportes')

@section('content')
@php
    $reportes = [
        [
            'titulo' => 'Reporte de ventas',
            'descripcion' => 'Resumen comercial por periodo, forma de pago, ventas por día y detalle de tickets.',
            'icono' => 'fa-chart-line',
            'ruta' => route('reportes.ventas'),
            'tono' => 'from-emerald-500 to-teal-500',
        ],
        [
            'titulo' => 'Productos más vendidos',
            'descripcion' => 'Ranking de rotación con unidades vendidas, precio promedio e ingresos por producto.',
            'icono' => 'fa-trophy',
            'ruta' => route('reportes.productos'),
            'tono' => 'from-blue-500 to-sky-500',
        ],
        [
            'titulo' => 'Estado de inventario',
            'descripcion' => 'Stock disponible, mínimos, productos agotados y valorización de compra y venta.',
            'icono' => 'fa-warehouse',
            'ruta' => route('reportes.inventario'),
            'tono' => 'from-amber-500 to-orange-500',
        ],
        [
            'titulo' => 'Vencimientos',
            'descripcion' => 'Control de lotes con fecha de vencimiento, productos críticos y próximos a vencer.',
            'icono' => 'fa-clock',
            'ruta' => route('reportes.vencimientos'),
            'tono' => 'from-rose-500 to-red-500',
        ],
    ];
@endphp

<div class="report-shell">
    <section class="report-hero p-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-[#b7775b] font-semibold">Análisis</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-800">Centro de reportes</h2>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl">Consulta ventas, rotación de productos, inventario y vencimientos con formatos preparados para revisión e impresión.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 bg-white border border-slate-200 text-slate-700 px-5 py-2.5 rounded-lg font-semibold hover:bg-slate-50">
                <i class="fas fa-arrow-left"></i>Volver al dashboard
            </a>
        </div>
    </section>

    <section class="report-card-grid">
        @foreach($reportes as $reporte)
            <a href="{{ $reporte['ruta'] }}" class="report-card p-6 hover:-translate-y-1 hover:shadow-lg transition group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $reporte['tono'] }} flex items-center justify-center text-white mb-5 group-hover:scale-105 transition">
                    <i class="fas {{ $reporte['icono'] }} text-xl"></i>
                </div>
                <h3 class="font-bold text-lg text-slate-800 mb-2">{{ $reporte['titulo'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $reporte['descripcion'] }}</p>
                <div class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[#9a5f46]">
                    Abrir reporte <i class="fas fa-arrow-right text-xs"></i>
                </div>
            </a>
        @endforeach
    </section>
</div>
@endsection
