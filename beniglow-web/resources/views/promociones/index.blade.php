@extends('layouts.app')
@section('title', 'Promociones')
@section('header', 'Promociones y descuentos')

@section('content')
@php $moneda = $empresaGlobal->moneda ?? 'S/'; @endphp

<div x-data="{
    open: {{ isset($errors) && $errors->any() ? 'true' : 'false' }},
    edit: null,
    dateValue(value, fallback) {
        return value ? String(value).slice(0, 10) : fallback;
    }
}">
    <div class="bg-white rounded-2xl shadow-md p-5 mb-5 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h3 class="font-bold text-slate-800">Promociones activas y programadas</h3>
            <p class="text-sm text-slate-500">Descuentos por producto, categoría o catálogo completo.</p>
        </div>
        <button type="button" @click="open = true; edit = null" class="gradient-primary text-white px-5 py-2.5 rounded-lg font-semibold flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i>Nueva promoción
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($promociones as $promocion)
            <div class="bg-white rounded-2xl shadow-md p-5 relative overflow-hidden hover:shadow-lg transition">
                @if($promocion->vigente)
                    <span class="absolute top-3 right-3 bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">VIGENTE</span>
                @else
                    <span class="absolute top-3 right-3 bg-slate-100 text-slate-500 px-2 py-1 rounded-full text-xs">Programada/Vencida</span>
                @endif

                <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-rose-500 rounded-2xl flex items-center justify-center text-white mb-3">
                    <i class="fas fa-percent text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg pr-24">{{ $promocion->nombre }}</h3>
                <p class="text-sm text-slate-500 mb-3">{{ $promocion->descripcion ?: 'Sin descripción' }}</p>

                <div class="space-y-1 text-sm">
                    <p><strong>Tipo:</strong> {{ ucwords(str_replace('_', ' ', $promocion->tipo)) }}</p>
                    <p><strong>Valor:</strong>
                        @if($promocion->tipo === 'descuento_porcentaje')
                            {{ $promocion->valor }}%
                        @else
                            {{ $moneda }}{{ number_format($promocion->valor, 2) }}
                        @endif
                    </p>
                    @if($promocion->producto)<p><strong>Producto:</strong> {{ $promocion->producto->nombre }}</p>@endif
                    @if($promocion->categoria)<p><strong>Categoría:</strong> {{ $promocion->categoria->nombre }}</p>@endif
                    <p class="text-xs text-slate-500"><i class="far fa-calendar"></i> {{ $promocion->fecha_inicio->format('d/m/Y') }} - {{ $promocion->fecha_fin->format('d/m/Y') }}</p>
                </div>

                <div class="flex gap-2 mt-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="edit = {!! Illuminate\Support\Js::from($promocion) !!}; open = true" class="flex-1 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg text-sm">
                        <i class="fas fa-edit mr-1"></i>Editar
                    </button>
                    <form method="POST" action="{{ route('promociones.destroy', $promocion->id) }}" class="flex-1" onsubmit="return confirm('¿Eliminar promoción?')">
                        @csrf
                        @method('DELETE')
                        <button class="w-full py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm">
                            <i class="fas fa-trash mr-1"></i>Eliminar
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-slate-400 py-12">No hay promociones registradas</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $promociones->links() }}</div>

    <div x-show="open" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display:none;">
        <div class="bg-white rounded-2xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto" @click.outside="open = false">
            <h3 class="text-xl font-bold mb-4" x-text="edit ? 'Editar promoción' : 'Nueva promoción'"></h3>

            <form :action="edit ? `/promociones/${edit.id}` : '{{ route('promociones.store') }}'" method="POST" class="space-y-3">
                @csrf
                <template x-if="edit"><input type="hidden" name="_method" value="PUT"></template>

                <div>
                    <label class="text-sm font-semibold">Nombre <span class="text-red-500">*</span></label>
                    <input name="nombre" :value="edit?.nombre || {!! Illuminate\Support\Js::from(old('nombre', '')) !!}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    @error('nombre')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-semibold">Descripción</label>
                    <textarea name="descripcion" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg" x-text="edit?.descripcion || {!! Illuminate\Support\Js::from(old('descripcion', '')) !!}"></textarea>
                    @error('descripcion')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-semibold">Tipo <span class="text-red-500">*</span></label>
                        <select name="tipo" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                            <option value="descuento_porcentaje" :selected="(edit?.tipo || '{{ old('tipo', 'descuento_porcentaje') }}') === 'descuento_porcentaje'">Descuento %</option>
                            <option value="descuento_fijo" :selected="(edit?.tipo || '{{ old('tipo') }}') === 'descuento_fijo'">Descuento fijo</option>
                            <option value="2x1" :selected="(edit?.tipo || '{{ old('tipo') }}') === '2x1'">2x1</option>
                            <option value="3x2" :selected="(edit?.tipo || '{{ old('tipo') }}') === '3x2'">3x2</option>
                            <option value="precio_especial" :selected="(edit?.tipo || '{{ old('tipo') }}') === 'precio_especial'">Precio especial</option>
                        </select>
                        @error('tipo')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold">Valor <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="valor" :value="edit?.valor || '{{ old('valor', 0) }}'" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                        @error('valor')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-semibold">Producto objetivo</label>
                        <select name="producto_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                            <option value="">- Cualquier producto -</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}" :selected="String(edit?.producto_id || '{{ old('producto_id') }}') === '{{ $producto->id }}'">{{ $producto->nombre }}</option>
                            @endforeach
                        </select>
                        @error('producto_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold">Categoría objetivo</label>
                        <select name="categoria_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                            <option value="">- Cualquier categoría -</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" :selected="String(edit?.categoria_id || '{{ old('categoria_id') }}') === '{{ $categoria->id }}'">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                        @error('categoria_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="text-sm font-semibold">Desde <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_inicio" :value="dateValue(edit?.fecha_inicio, '{{ old('fecha_inicio', now()->toDateString()) }}')" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                        @error('fecha_inicio')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold">Hasta <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_fin" :value="dateValue(edit?.fecha_fin, '{{ old('fecha_fin', now()->addDays(30)->toDateString()) }}')" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                        @error('fecha_fin')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold">Cant. mín.</label>
                        <input type="number" name="cantidad_minima" :value="edit?.cantidad_minima || '{{ old('cantidad_minima', 1) }}'" min="1" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                        @error('cantidad_minima')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <template x-if="edit">
                    <label class="flex gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="activo" value="1" :checked="edit?.activo" class="rounded text-emerald-500">
                        Activa
                    </label>
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
