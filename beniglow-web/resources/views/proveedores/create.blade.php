@extends('layouts.app')
@section('title', 'Nuevo proveedor')
@section('header', 'Nuevo proveedor')

@section('content')
<form method="POST" action="{{ route('proveedores.store') }}" class="bg-white rounded-2xl shadow-md p-6 max-w-4xl border border-slate-100">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label class="block text-sm font-semibold mb-1">Código</label><input type="text" name="codigo" value="{{ old('codigo', $codigo) }}" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg"></div>
        <div><label class="block text-sm font-semibold mb-1">RUC / NIT</label><input type="text" name="ruc_nit" value="{{ old('ruc_nit') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg"></div>
        <div class="md:col-span-2"><label class="block text-sm font-semibold mb-1">Razón social *</label><input type="text" name="razon_social" value="{{ old('razon_social') }}" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg"></div>
        <div><label class="block text-sm font-semibold mb-1">Nombre comercial</label><input type="text" name="nombre_comercial" value="{{ old('nombre_comercial') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg"></div>
        <div><label class="block text-sm font-semibold mb-1">Contacto</label><input type="text" name="contacto" value="{{ old('contacto') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg"></div>
        <div><label class="block text-sm font-semibold mb-1">Teléfono</label><input type="text" name="telefono" value="{{ old('telefono') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg"></div>
        <div><label class="block text-sm font-semibold mb-1">Email</label><input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg"></div>
        <div><label class="block text-sm font-semibold mb-1">Ciudad</label><input type="text" name="ciudad" value="{{ old('ciudad') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg"></div>
        <div class="md:col-span-2"><label class="block text-sm font-semibold mb-1">Dirección / URL</label><input type="text" name="direccion" value="{{ old('direccion') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg"></div>
        <div class="md:col-span-2"><label class="block text-sm font-semibold mb-1">Observaciones</label><textarea name="observaciones" rows="3" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">{{ old('observaciones') }}</textarea></div>
    </div>
    <div class="flex gap-3 mt-6">
        <a href="{{ route('proveedores.index') }}" class="flex-1 text-center py-3 bg-slate-100 text-slate-700 rounded-lg font-semibold">Cancelar</a>
        <button class="flex-1 gradient-primary text-white py-3 rounded-lg font-semibold"><i class="fas fa-save mr-2"></i>Guardar</button>
    </div>
</form>
@endsection
