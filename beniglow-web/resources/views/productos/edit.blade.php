@extends('layouts.app')
@section('title', 'Editar Producto')
@section('header', 'Editar: ' . $producto->nombre)

@section('content')
<form method="POST" action="{{ route('productos.update', $producto->id) }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    @csrf @method('PUT')

    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100"><i class="fas fa-info-circle text-emerald-500 mr-2"></i>Información general</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Código</label>
                    <input type="text" name="codigo" value="{{ old('codigo', $producto->codigo) }}" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Código de barras</label>
                    <input type="text" name="codigo_barras" value="{{ old('codigo_barras', $producto->codigo_barras) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción</label>
                    <textarea name="descripcion" rows="2" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">{{ old('descripcion', $producto->descripcion) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Categoría</label>
                    <select name="categoria_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                        <option value="">- Sin categoría -</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c->id }}" {{ $producto->categoria_id == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Proveedor</label>
                    <select name="proveedor_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                        <option value="">- Sin proveedor -</option>
                        @foreach($proveedores as $p)
                            <option value="{{ $p->id }}" {{ $producto->proveedor_id == $p->id ? 'selected' : '' }}>{{ $p->razon_social }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Unidad de medida <span class="text-red-500">*</span></label>
                    <select name="unidad_medida" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                        @foreach(['UND'=>'Unidad','KG'=>'Kilogramo','LT'=>'Litro','GR'=>'Gramo','ML'=>'Mililitro','CAJA'=>'Caja','PAQ'=>'Paquete'] as $k=>$v)
                            <option value="{{ $k }}" {{ $producto->unidad_medida == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Ubicación</label>
                    <input type="text" name="ubicacion" value="{{ $producto->ubicacion }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100"><i class="fas fa-spa text-emerald-500 mr-2"></i>Catálogo web y cosmética</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Slug web</label>
                    <input type="text" name="slug" value="{{ old('slug', $producto->slug) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Marca</label>
                    <input type="text" name="marca" value="{{ old('marca', $producto->marca) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Línea / colección</label>
                    <input type="text" name="linea" value="{{ old('linea', $producto->linea) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tono / Variante</label>
                    <input type="text" name="tono" value="{{ old('tono', $producto->tono) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Presentación</label>
                    <input type="text" name="presentacion" value="{{ old('presentacion', $producto->presentacion) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tipo de piel</label>
                    <input type="text" name="tipo_piel" value="{{ old('tipo_piel', $producto->tipo_piel) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Acabado</label>
                    <input type="text" name="acabado" value="{{ old('acabado', $producto->acabado) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Volumen / Peso</label>
                    <input type="text" name="volumen" value="{{ old('volumen', $producto->volumen) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Ingredientes clave</label>
                    <textarea name="ingredientes_clave" rows="2" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">{{ old('ingredientes_clave', $producto->ingredientes_clave) }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100"><i class="fas fa-dollar-sign text-emerald-500 mr-2"></i>Precios</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Precio de compra <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="precio_compra" value="{{ $producto->precio_compra }}" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Precio de venta <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="precio_venta" value="{{ $producto->precio_venta }}" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Precio Mayoreo</label>
                    <input type="number" step="0.01" name="precio_mayoreo" value="{{ $producto->precio_mayoreo }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Cant. Mín. Mayoreo</label>
                    <input type="number" name="cantidad_mayoreo" value="{{ $producto->cantidad_mayoreo }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Precio oferta web</label>
                    <input type="number" step="0.01" name="precio_oferta" value="{{ old('precio_oferta', $producto->precio_oferta) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Orden en web</label>
                    <input type="number" name="orden_web" value="{{ old('orden_web', $producto->orden_web) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Oferta desde</label>
                    <input type="date" name="oferta_inicio" value="{{ old('oferta_inicio', $producto->oferta_inicio?->format('Y-m-d')) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Oferta hasta</label>
                    <input type="date" name="oferta_fin" value="{{ old('oferta_fin', $producto->oferta_fin?->format('Y-m-d')) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100"><i class="fas fa-warehouse text-emerald-500 mr-2"></i>Inventario</h3>
            <p class="text-sm text-slate-600 mb-3">Stock actual: <strong>{{ number_format($producto->stock, 2) }}</strong> {{ $producto->unidad_medida }}. Para ajustar el stock use el botón "Ajustar stock" en la vista del producto.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Stock mínimo <span class="text-red-500">*</span></label>
                    <input type="number" step="0.001" name="stock_minimo" value="{{ $producto->stock_minimo }}" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Stock máximo</label>
                    <input type="number" step="0.001" name="stock_maximo" value="{{ $producto->stock_maximo }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Lote</label>
                    <input type="text" name="lote" value="{{ $producto->lote }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Fecha de vencimiento</label>
                    <input type="date" name="fecha_vencimiento" value="{{ $producto->fecha_vencimiento?->format('Y-m-d') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4"><i class="fas fa-image text-emerald-500 mr-2"></i>Imagen</h3>
            @if($producto->imagen)
                <img src="{{ $producto->imagen_url }}" class="w-full aspect-square object-cover rounded-lg mb-3">
            @endif
            <input type="file" name="imagen" accept="image/*" class="block w-full text-sm">
            <label class="block text-sm font-semibold text-slate-700 mt-4 mb-1">Texto alt web</label>
            <input type="text" name="imagen_alt" value="{{ old('imagen_alt', $producto->imagen_alt) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4">Opciones</h3>
            <input type="hidden" name="activo" value="0">
            <label class="flex items-center gap-3 mb-3 cursor-pointer">
                <input type="checkbox" name="activo" value="1" {{ $producto->activo ? 'checked' : '' }} class="rounded text-emerald-500">
                <span class="text-sm text-slate-700">Activo</span>
            </label>
            <input type="hidden" name="controla_stock" value="0">
            <label class="flex items-center gap-3 mb-3 cursor-pointer">
                <input type="checkbox" name="controla_stock" value="1" {{ $producto->controla_stock ? 'checked' : '' }} class="rounded">
                <span class="text-sm">Controla inventario</span>
            </label>
            <input type="hidden" name="aplica_impuesto" value="0">
            <label class="flex items-center gap-3 mb-3 cursor-pointer">
                <input type="checkbox" name="aplica_impuesto" value="1" {{ $producto->aplica_impuesto ? 'checked' : '' }} class="rounded">
                <span class="text-sm">Aplica impuesto</span>
            </label>
            <input type="hidden" name="destacado" value="0">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="destacado" value="1" {{ $producto->destacado ? 'checked' : '' }} class="rounded">
                <span class="text-sm">Destacado en mostrador</span>
            </label>
            <input type="hidden" name="visible_web" value="0">
            <label class="flex items-center gap-3 mt-3 cursor-pointer">
                <input type="checkbox" name="visible_web" value="1" {{ $producto->visible_web ? 'checked' : '' }} class="rounded">
                <span class="text-sm">Visible en página web</span>
            </label>
            <input type="hidden" name="destacado_web" value="0">
            <label class="flex items-center gap-3 mt-3 cursor-pointer">
                <input type="checkbox" name="destacado_web" value="1" {{ $producto->destacado_web ? 'checked' : '' }} class="rounded">
                <span class="text-sm">Destacado en web</span>
            </label>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4"><i class="fas fa-globe text-emerald-500 mr-2"></i>SEO</h3>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Título SEO</label>
            <input type="text" name="meta_title" value="{{ old('meta_title', $producto->meta_title) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg mb-3">
            <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción SEO</label>
            <textarea name="meta_description" rows="3" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">{{ old('meta_description', $producto->meta_description) }}</textarea>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <button type="submit" class="w-full gradient-primary text-white py-3 rounded-lg font-semibold"><i class="fas fa-save mr-2"></i>Actualizar</button>
            <a href="{{ route('productos.index') }}" class="block text-center mt-2 py-3 text-slate-600 hover:bg-slate-100 rounded-lg">Cancelar</a>
        </div>
    </div>
</form>
@endsection
