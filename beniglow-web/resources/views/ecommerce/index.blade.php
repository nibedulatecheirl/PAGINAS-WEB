@extends('layouts.app')
@section('title', 'Panel e-commerce')
@section('header', 'Panel e-commerce')

@section('content')
@php
    $metricas = [
        ['titulo' => 'Productos web', 'valor' => $resumen['productos_web'], 'icono' => 'fa-box-open', 'color' => 'emerald'],
        ['titulo' => 'Destacados', 'valor' => $resumen['productos_destacados'], 'icono' => 'fa-star', 'color' => 'amber'],
        ['titulo' => 'Categorías', 'valor' => $resumen['categorias_activas'], 'icono' => 'fa-tags', 'color' => 'pink'],
        ['titulo' => 'Promociones', 'valor' => $resumen['promociones_vigentes'], 'icono' => 'fa-percent', 'color' => 'rose'],
        ['titulo' => 'Pagos pendientes', 'valor' => $resumen['pedidos_pendientes'], 'icono' => 'fa-credit-card', 'color' => 'yellow'],
        ['titulo' => 'En preparación', 'valor' => $resumen['pedidos_preparando'], 'icono' => 'fa-bag-shopping', 'color' => 'blue'],
    ];

    $colorClasses = [
        'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'amber' => 'bg-amber-50 text-amber-700 border-amber-100',
        'pink' => 'bg-pink-50 text-pink-700 border-pink-100',
        'rose' => 'bg-rose-50 text-rose-700 border-rose-100',
        'yellow' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
        'blue' => 'bg-blue-50 text-blue-700 border-blue-100',
    ];
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4">
        @foreach($metricas as $metrica)
            <div class="bg-white rounded-2xl shadow-md p-5 border border-slate-100">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center {{ $colorClasses[$metrica['color']] }}">
                    <i class="fas {{ $metrica['icono'] }}"></i>
                </div>
                <p class="text-2xl font-bold text-slate-800 mt-4">{{ number_format($metrica['valor']) }}</p>
                <p class="text-sm text-slate-500">{{ $metrica['titulo'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
        <section class="xl:col-span-2 bg-white rounded-2xl shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Revisión para publicar</h3>
                    <p class="text-sm text-slate-500">Validaciones mínimas antes de conectar o exponer productos en la página pública.</p>
                </div>
                <a href="{{ route('storefront') }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-sm font-semibold">
                    <i class="fas fa-store"></i> Ver tienda pública
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($revision as $item)
                    @php $ok = $item['valor'] === 0; @endphp
                    <article class="rounded-2xl border {{ $ok ? 'border-emerald-100 bg-emerald-50/60' : 'border-amber-100 bg-amber-50/70' }} p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h4 class="font-bold text-slate-800">{{ $item['titulo'] }}</h4>
                                <p class="text-sm text-slate-600 mt-1">{{ $item['detalle'] }}</p>
                            </div>
                            <span class="min-w-10 h-10 rounded-xl flex items-center justify-center font-bold {{ $ok ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $item['valor'] }}
                            </span>
                        </div>
                        <a href="{{ $item['ruta'] }}" class="inline-flex items-center gap-2 mt-4 text-sm font-semibold {{ $ok ? 'text-emerald-700' : 'text-amber-700' }}">
                            {{ $item['accion'] }} <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>

        <aside class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-2">Accesos de gestión</h3>
            <p class="text-sm text-slate-500 mb-4">Rutas principales para dejar el catálogo listo y administrable.</p>
            <div class="space-y-2">
                <a href="{{ route('productos.index', ['visible_web' => 'si']) }}" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50">
                    <span class="font-semibold text-slate-700"><i class="fas fa-box-open text-emerald-600 mr-2"></i>Productos web</span>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </a>
                <a href="{{ route('categorias.index') }}" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50">
                    <span class="font-semibold text-slate-700"><i class="fas fa-tags text-pink-600 mr-2"></i>Categorías</span>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </a>
                <a href="{{ route('promociones.index') }}" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50">
                    <span class="font-semibold text-slate-700"><i class="fas fa-percent text-rose-600 mr-2"></i>Promociones</span>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </a>
                <a href="{{ route('pedidos-web.index') }}" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50">
                    <span class="font-semibold text-slate-700"><i class="fas fa-bag-shopping text-blue-600 mr-2"></i>Pedidos web</span>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </a>
            </div>
        </aside>
    </div>

    <section class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3 mb-5">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Información necesaria por módulo</h3>
                <p class="text-sm text-slate-500">Campos que conviene tener completos para operar la boutique cosmética desde el sistema.</p>
            </div>
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-sm">
                <i class="fas fa-circle-info"></i> Base para la integración web
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            @foreach($campos as $grupo)
                <article class="rounded-2xl border border-slate-100 p-5">
                    <h4 class="font-bold text-slate-800 mb-3">{{ $grupo['titulo'] }}</h4>
                    <ul class="space-y-2">
                        @foreach($grupo['items'] as $item)
                            <li class="flex items-start gap-2 text-sm text-slate-600">
                                <i class="fas fa-check text-emerald-600 mt-0.5"></i>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection
