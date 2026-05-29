@extends('layouts.app')
@section('title', 'Nuevo Producto')
@section('header', 'Nuevo Producto')

@section('content')
<form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    @csrf

    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100"><i class="fas fa-info-circle text-emerald-500 mr-2"></i>Información general</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Código <span class="text-red-500">*</span></label>
                    <input type="text" name="codigo" value="{{ old('codigo', $codigo) }}" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Código de barras</label>
                    <input type="text" name="codigo_barras" value="{{ old('codigo_barras') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción</label>
                    <textarea name="descripcion" rows="2" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">{{ old('descripcion') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Categoría</label>
                    <select name="categoria_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                        <option value="">- Sin categoría -</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Proveedor</label>
                    <select name="proveedor_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                        <option value="">- Sin proveedor -</option>
                        @foreach($proveedores as $p)
                            <option value="{{ $p->id }}">{{ $p->razon_social }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Unidad de medida <span class="text-red-500">*</span></label>
                    <select name="unidad_medida" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                        <option value="UND">Unidad</option>
                        <option value="KG">Kilogramo</option>
                        <option value="LT">Litro</option>
                        <option value="GR">Gramo</option>
                        <option value="ML">Mililitro</option>
                        <option value="CAJA">Caja</option>
                        <option value="PAQ">Paquete</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Ubicación</label>
                    <input type="text" name="ubicacion" placeholder="Ej: Pasillo 3 Estante 2" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100"><i class="fas fa-spa text-emerald-500 mr-2"></i>Catálogo web y cosmética</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Slug web</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Se genera automáticamente si queda vacío" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Marca</label>
                    <input type="text" name="marca" value="{{ old('marca') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Línea / colección</label>
                    <input type="text" name="linea" value="{{ old('linea') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tono / Variante</label>
                    <input type="text" name="tono" value="{{ old('tono') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Presentación</label>
                    <input type="text" name="presentacion" value="{{ old('presentacion') }}" placeholder="Frasco, tubo, paleta, set" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tipo de piel</label>
                    <input type="text" name="tipo_piel" value="{{ old('tipo_piel') }}" placeholder="Mixta, grasa, seca, sensible" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Acabado</label>
                    <input type="text" name="acabado" value="{{ old('acabado') }}" placeholder="Mate, glow, natural" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Volumen / Peso</label>
                    <input type="text" name="volumen" value="{{ old('volumen') }}" placeholder="30 ml, 50 g" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Ingredientes clave</label>
                    <textarea name="ingredientes_clave" rows="2" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">{{ old('ingredientes_clave') }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100"><i class="fas fa-dollar-sign text-emerald-500 mr-2"></i>Precios</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Precio de compra <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="precio_compra" value="0" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Precio de venta <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="precio_venta" value="0" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Precio Mayoreo</label>
                    <input type="number" step="0.01" name="precio_mayoreo" value="0" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Cantidad Mínima Mayoreo</label>
                    <input type="number" name="cantidad_mayoreo" value="0" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Precio oferta web</label>
                    <input type="number" step="0.01" name="precio_oferta" value="{{ old('precio_oferta') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Orden en web</label>
                    <input type="number" name="orden_web" value="{{ old('orden_web', 0) }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Oferta desde</label>
                    <input type="date" name="oferta_inicio" value="{{ old('oferta_inicio') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Oferta hasta</label>
                    <input type="date" name="oferta_fin" value="{{ old('oferta_fin') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100"><i class="fas fa-warehouse text-emerald-500 mr-2"></i>Inventario</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Stock inicial <span class="text-red-500">*</span></label>
                    <input type="number" step="0.001" name="stock" value="0" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Stock mínimo <span class="text-red-500">*</span></label>
                    <input type="number" step="0.001" name="stock_minimo" value="0" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Stock máximo</label>
                    <input type="number" step="0.001" name="stock_maximo" value="0" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Lote</label>
                    <input type="text" name="lote" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Fecha de vencimiento</label>
                    <input type="date" name="fecha_vencimiento" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4"><i class="fas fa-image text-emerald-500 mr-2"></i>Imagen</h3>
            <input type="file" name="imagen" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            <label class="block text-sm font-semibold text-slate-700 mt-4 mb-1">Texto alt web</label>
            <input type="text" name="imagen_alt" value="{{ old('imagen_alt') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4"><i class="fas fa-cog text-emerald-500 mr-2"></i>Opciones</h3>
            <input type="hidden" name="controla_stock" value="0">
            <label class="flex items-center gap-3 mb-3 cursor-pointer">
                <input type="checkbox" name="controla_stock" value="1" checked class="rounded text-emerald-500">
                <span class="text-sm text-slate-700">Controla inventario</span>
            </label>
            <input type="hidden" name="aplica_impuesto" value="0">
            <label class="flex items-center gap-3 mb-3 cursor-pointer">
                <input type="checkbox" name="aplica_impuesto" value="1" checked class="rounded text-emerald-500">
                <span class="text-sm text-slate-700">Aplica impuesto</span>
            </label>
            <input type="hidden" name="destacado" value="0">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="destacado" value="1" class="rounded text-emerald-500">
                <span class="text-sm text-slate-700">Destacado en mostrador</span>
            </label>
            <input type="hidden" name="visible_web" value="0">
            <label class="flex items-center gap-3 mt-3 cursor-pointer">
                <input type="checkbox" name="visible_web" value="1" checked class="rounded text-emerald-500">
                <span class="text-sm text-slate-700">Visible en página web</span>
            </label>
            <input type="hidden" name="destacado_web" value="0">
            <label class="flex items-center gap-3 mt-3 cursor-pointer">
                <input type="checkbox" name="destacado_web" value="1" class="rounded text-emerald-500">
                <span class="text-sm text-slate-700">Destacado en web</span>
            </label>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="font-bold text-slate-800 mb-4"><i class="fas fa-globe text-emerald-500 mr-2"></i>SEO</h3>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Título SEO</label>
            <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg mb-3">
            <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción SEO</label>
            <textarea name="meta_description" rows="3" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">{{ old('meta_description') }}</textarea>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <button type="submit" class="w-full gradient-primary text-white py-3 rounded-lg font-semibold hover:shadow-lg transition">
                <i class="fas fa-save mr-2"></i>Guardar Producto
            </button>
            <a href="{{ route('productos.index') }}" class="block text-center mt-2 py-3 text-slate-600 hover:bg-slate-100 rounded-lg">Cancelar</a>
        </div>
    </div>
</form>
@endsection
