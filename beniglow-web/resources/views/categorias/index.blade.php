@extends('layouts.app')
@section('title', 'Categorías')
@section('header', 'Categorías de Productos')

@section('content')
<div x-data="{ open: {{ isset($errors) && $errors->any() ? 'true' : 'false' }}, edit: null }" class="space-y-5">

<div class="bg-white rounded-2xl shadow-md p-5 flex justify-between items-center">
    <h3 class="font-bold text-slate-800">Listado de Categorías</h3>
    <button @click="open = true; edit = null" class="gradient-primary text-white px-5 py-2.5 rounded-lg font-semibold flex items-center gap-2">
        <i class="fas fa-plus"></i>Nueva Categoría
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    @forelse($categorias as $c)
        <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-lg transition" style="border-top: 4px solid {{ $c->color }}">
            <div class="flex justify-between items-start mb-3">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:{{ $c->color }}20">
                    <i class="fas fa-{{ $c->icono }} text-xl" style="color:{{ $c->color }}"></i>
                </div>
                <div class="flex gap-1">
                    <button type="button" @click="edit = {!! Illuminate\Support\Js::from($c) !!}; open = true" class="p-2 hover:bg-yellow-50 text-yellow-600 rounded-lg"><i class="fas fa-edit"></i></button>
                    <form method="POST" action="{{ route('categorias.destroy', $c->id) }}" class="inline" onsubmit="return confirm('¿Eliminar categoría?')">
                        @csrf @method('DELETE')
                        <button class="p-2 hover:bg-red-50 text-red-600 rounded-lg"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            <h3 class="font-bold text-slate-800">{{ $c->nombre }}</h3>
            <p class="text-sm text-slate-500 mb-2">{{ $c->descripcion ?? 'Sin descripción' }}</p>
            <div class="flex items-center gap-2 text-xs">
                <span class="bg-slate-100 px-2 py-1 rounded-full"><i class="fas fa-box mr-1"></i>{{ $c->productos_count }} productos</span>
                @if(!$c->activo)
                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full">Inactivo</span>
                @endif
            </div>
        </div>
    @empty
        <p class="col-span-full text-center text-slate-400 py-12">No hay categorías</p>
    @endforelse
</div>
{{ $categorias->links() }}

<!-- Modal -->
<div x-show="open" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display:none;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6" @click.outside="open = false">
        <h3 class="text-xl font-bold mb-4" x-text="edit ? 'Editar Categoría' : 'Nueva Categoría'"></h3>
        <form :action="edit ? `/categorias/${edit.id}` : '{{ route('categorias.store') }}'" method="POST" class="space-y-3">
            @csrf
            <template x-if="edit">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <div>
                <label class="block text-sm font-semibold mb-1">Nombre <span class="text-red-500">*</span></label>
                <input type="text" name="nombre" :value="edit?.nombre || ''" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                @error('nombre')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Descripción</label>
                <input type="text" name="descripcion" :value="edit?.descripcion || ''" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                @error('descripcion')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold mb-1">Color <span class="text-red-500">*</span></label>
                    <input type="color" name="color" :value="edit?.color || '#b7775b'" required class="w-full h-10 border border-slate-300 rounded-lg">
                    @error('color')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Ícono <span class="text-red-500">*</span></label>
                    <select name="icono" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                        @foreach(['spa','wand-magic-sparkles','spray-can-sparkles','pump-soap','brush','gift','leaf','hand-holding-heart','heart','bag-shopping','tags','percent'] as $i)
                            <option value="{{ $i }}" x-bind:selected="edit?.icono === '{{ $i }}'">{{ $i }}</option>
                        @endforeach
                    </select>
                    @error('icono')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <template x-if="edit">
                <label class="flex items-center gap-2"><input type="checkbox" name="activo" value="1" :checked="edit?.activo"> Activo</label>
            </template>
            <div class="flex gap-2 pt-3">
                <button type="button" @click="open = false" class="flex-1 py-2.5 bg-slate-200 rounded-lg">Cancelar</button>
                <button type="submit" class="flex-1 py-2.5 gradient-primary text-white rounded-lg font-semibold">Guardar</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
