@extends('layouts.app')
@section('title', 'Proveedores')
@section('header', 'Gestión de proveedores')

@section('content')
<div class="bg-white rounded-2xl shadow-md p-5 mb-5 flex flex-col md:flex-row gap-3 justify-between">
    <form method="GET" class="flex-1 flex gap-2 max-w-2xl">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input name="buscar" value="{{ request('buscar') }}" placeholder="Buscar proveedor..." class="w-full pl-12 pr-4 py-2.5 border border-slate-300 rounded-lg">
        </div>
        <button class="bg-slate-800 text-white px-4 py-2.5 rounded-lg" aria-label="Buscar proveedores"><i class="fas fa-search"></i></button>
    </form>
    <a href="{{ route('proveedores.create') }}" class="gradient-primary text-white px-5 py-2.5 rounded-lg font-semibold flex items-center justify-center gap-2">
        <i class="fas fa-plus"></i>Nuevo proveedor
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse($proveedores as $p)
        <article class="bg-white rounded-2xl shadow-md p-5 hover:shadow-lg transition border border-slate-100">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-truck-loading text-emerald-600 text-xl"></i>
                </div>
                <div class="flex gap-1">
                    <a href="{{ route('proveedores.show', $p) }}" title="Ver proveedor" class="w-9 h-9 grid place-items-center hover:bg-blue-50 text-blue-600 rounded-lg"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('proveedores.edit', $p) }}" title="Editar proveedor" class="w-9 h-9 grid place-items-center hover:bg-yellow-50 text-yellow-600 rounded-lg"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{ route('proveedores.destroy', $p) }}" onsubmit="return confirm('¿Desactivar este proveedor?')">
                        @csrf
                        @method('DELETE')
                        <button title="Desactivar proveedor" class="w-9 h-9 grid place-items-center hover:bg-red-50 text-red-600 rounded-lg"><i class="fas fa-ban"></i></button>
                    </form>
                </div>
            </div>
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="font-bold text-slate-800 leading-tight">{{ $p->razon_social }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ $p->codigo }} · {{ $p->ruc_nit ?: 'Sin RUC/NIT' }}</p>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full {{ $p->activo ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $p->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            <div class="space-y-1.5 text-sm mt-4 text-slate-600">
                @if($p->nombre_comercial)<p><i class="fas fa-store text-slate-400 w-5"></i>{{ $p->nombre_comercial }}</p>@endif
                @if($p->contacto)<p><i class="fas fa-user text-slate-400 w-5"></i>{{ $p->contacto }}</p>@endif
                @if($p->telefono)<p><i class="fas fa-phone text-slate-400 w-5"></i>{{ $p->telefono }}</p>@endif
                @if($p->email)<p><i class="fas fa-envelope text-slate-400 w-5"></i>{{ $p->email }}</p>@endif
                @if($p->ciudad)<p><i class="fas fa-map-marker-alt text-slate-400 w-5"></i>{{ $p->ciudad }}</p>@endif
            </div>
        </article>
    @empty
        <p class="col-span-full text-center text-slate-400 py-12">Sin proveedores registrados</p>
    @endforelse
</div>

<div class="mt-4">{{ $proveedores->withQueryString()->links() }}</div>
@endsection
