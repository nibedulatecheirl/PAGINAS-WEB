@extends('layouts.app')
@section('title', $proveedor->razon_social)
@section('header', $proveedor->razon_social)

@section('content')
@php $moneda = $empresaGlobal->moneda ?? 'S/'; @endphp
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <section class="bg-white rounded-2xl shadow-md p-6 border border-slate-100">
        <div class="flex items-start justify-between gap-4">
            <div class="w-20 h-20 bg-emerald-50 rounded-2xl flex items-center justify-center">
                <i class="fas fa-truck-loading text-emerald-600 text-3xl"></i>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full {{ $proveedor->activo ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                {{ $proveedor->activo ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
        <h2 class="text-xl font-bold mt-5">{{ $proveedor->razon_social }}</h2>
        <p class="text-sm text-slate-500">{{ $proveedor->codigo }}</p>
        <div class="space-y-2 text-sm mt-4 text-slate-700">
            @if($proveedor->nombre_comercial)<p><i class="fas fa-store text-slate-400 w-5"></i> {{ $proveedor->nombre_comercial }}</p>@endif
            @if($proveedor->ruc_nit)<p><i class="fas fa-id-card text-slate-400 w-5"></i> {{ $proveedor->ruc_nit }}</p>@endif
            @if($proveedor->contacto)<p><i class="fas fa-user text-slate-400 w-5"></i> {{ $proveedor->contacto }}</p>@endif
            @if($proveedor->telefono)<p><i class="fas fa-phone text-slate-400 w-5"></i> {{ $proveedor->telefono }}</p>@endif
            @if($proveedor->email)<p><i class="fas fa-envelope text-slate-400 w-5"></i> {{ $proveedor->email }}</p>@endif
            @if($proveedor->direccion)<p><i class="fas fa-map-marker-alt text-slate-400 w-5"></i> {{ $proveedor->direccion }}</p>@endif
            @if($proveedor->ciudad)<p><i class="fas fa-city text-slate-400 w-5"></i> {{ $proveedor->ciudad }}</p>@endif
        </div>
        @if($proveedor->observaciones)
            <div class="mt-5 rounded-xl bg-slate-50 border border-slate-100 p-4 text-sm text-slate-600">
                {{ $proveedor->observaciones }}
            </div>
        @endif
        <div class="grid grid-cols-2 gap-3 mt-5">
            <a href="{{ route('proveedores.index') }}" class="text-center bg-slate-100 text-slate-700 py-2.5 rounded-lg font-semibold">Volver</a>
            <a href="{{ route('proveedores.edit', $proveedor) }}" class="text-center gradient-primary text-white py-2.5 rounded-lg font-semibold"><i class="fas fa-edit mr-1"></i>Editar</a>
        </div>
    </section>

    <section class="lg:col-span-2 bg-white rounded-2xl shadow-md p-6 border border-slate-100">
        <h3 class="font-bold mb-4"><i class="fas fa-receipt mr-2 text-emerald-500"></i>Historial de compras</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs uppercase text-slate-500 border-b">
                    <tr><th class="text-left py-2">N°</th><th class="text-left py-2">Fecha</th><th class="text-left py-2">Factura</th><th class="text-right py-2">Total</th></tr>
                </thead>
                <tbody>
                @forelse($proveedor->compras as $c)
                    <tr class="border-b">
                        <td class="py-2 font-mono text-xs">{{ $c->numero }}</td>
                        <td class="py-2">{{ $c->fecha_compra->format('d/m/Y') }}</td>
                        <td class="py-2">{{ $c->numero_factura ?: '—' }}</td>
                        <td class="py-2 text-right font-bold text-emerald-600">{{ $moneda }}{{ number_format($c->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-8 text-slate-400">Sin compras registradas</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
