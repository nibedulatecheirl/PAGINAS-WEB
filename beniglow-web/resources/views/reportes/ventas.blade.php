@extends('layouts.app')
@section('title', 'Reporte de ventas')
@section('header', 'Reporte de ventas')

@section('content')
@php
    $moneda = $empresaGlobal->moneda ?? 'S/';
    $empresa = $empresaGlobal->nombre_comercial ?? 'BeniGlow Store';
    $razon = $empresaGlobal->razon_social ?? 'Beniglow E.I.R.L.';
    $periodo = \Carbon\Carbon::parse($desde)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($hasta)->format('d/m/Y');
    $ticketPromedio = $totales['cantidad'] > 0 ? $totales['total'] / $totales['cantidad'] : 0;
@endphp

<div class="report-shell">
    <div class="report-print-header">
        <div>
            <p class="report-print-title">Reporte de ventas</p>
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
                <p class="text-xs uppercase tracking-[0.22em] text-[#b7775b] font-semibold">Ventas</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-800">Resumen comercial</h2>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl">Ventas completadas entre {{ $periodo }}. Incluye ventas web confirmadas y ventas de mostrador completadas.</p>
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
            <p class="text-xs uppercase tracking-wide text-slate-500">Total vendido</p>
            <p class="mt-2 text-2xl font-bold report-kpi-accent">{{ $moneda }}{{ number_format($totales['total'], 2) }}</p>
            <p class="mt-1 text-xs text-slate-500">{{ $totales['cantidad'] }} tickets</p>
        </div>
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Ticket promedio</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $moneda }}{{ number_format($ticketPromedio, 2) }}</p>
            <p class="mt-1 text-xs text-slate-500">Promedio por venta</p>
        </div>
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Impuestos</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $moneda }}{{ number_format($totales['impuesto'], 2) }}</p>
            <p class="mt-1 text-xs text-slate-500">Incluidos en el periodo</p>
        </div>
        <div class="report-kpi p-5">
            <p class="text-xs uppercase tracking-wide text-slate-500">Descuentos</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $moneda }}{{ number_format($totales['descuento'], 2) }}</p>
            <p class="mt-1 text-xs text-slate-500">Aplicados a ventas</p>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="report-sheet p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800">Por forma de pago</h3>
                <span class="text-xs text-slate-500">{{ $porFormaPago->count() }} métodos</span>
            </div>
            <div class="report-table-wrapper">
                <table class="report-table text-sm">
                    <thead>
                        <tr>
                            <th class="text-left">Forma</th>
                            <th class="text-right">Tickets</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($porFormaPago as $forma => $datos)
                        <tr>
                            <td class="font-semibold">{{ ucfirst(str_replace('_', ' ', $forma)) }}</td>
                            <td class="text-right">{{ $datos['cantidad'] }}</td>
                            <td class="text-right font-bold">{{ $moneda }}{{ number_format($datos['total'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-slate-400">Sin ventas en el periodo</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="report-sheet p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800">Ventas por día</h3>
                <span class="text-xs text-slate-500">{{ $porDia->count() }} días</span>
            </div>
            <div class="report-table-wrapper">
                <table class="report-table text-sm">
                    <thead>
                        <tr>
                            <th class="text-left">Fecha</th>
                            <th class="text-right">Tickets</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($porDia as $fecha => $datos)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</td>
                            <td class="text-right">{{ $datos['cantidad'] }}</td>
                            <td class="text-right font-bold">{{ $moneda }}{{ number_format($datos['total'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-slate-400">Sin ventas diarias para mostrar</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="report-sheet p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
            <h3 class="font-bold text-slate-800">Detalle de ventas</h3>
            <span class="text-xs text-slate-500">{{ $ventas->count() }} tickets encontrados</span>
        </div>
        <div class="report-table-wrapper">
            <table class="report-table text-sm">
                <thead>
                    <tr>
                        <th class="text-left">Ticket</th>
                        <th class="text-left">Fecha</th>
                        <th class="text-left">Cliente</th>
                        <th class="text-left">Usuario</th>
                        <th class="text-left">Pago</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right">Impuesto</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($ventas as $v)
                    <tr>
                        <td class="font-mono text-xs">{{ $v->numero_ticket }}</td>
                        <td>{{ $v->fecha_venta->format('d/m/Y H:i') }}</td>
                        <td>{{ $v->cliente?->nombre_completo ?? 'Genérico' }}</td>
                        <td>{{ $v->user->name }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $v->forma_pago)) }}</td>
                        <td class="text-right">{{ $moneda }}{{ number_format($v->subtotal, 2) }}</td>
                        <td class="text-right">{{ $moneda }}{{ number_format($v->impuesto, 2) }}</td>
                        <td class="text-right font-bold">{{ $moneda }}{{ number_format($v->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-slate-400">Sin ventas en el periodo seleccionado</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
