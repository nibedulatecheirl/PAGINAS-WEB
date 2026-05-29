@extends('layouts.app')
@section('title', 'Nueva Compra')
@section('header', 'Registrar Compra')

@section('content')
@php $moneda = $empresaGlobal->moneda ?? 'S/'; @endphp

<div class="bg-white rounded-2xl shadow-md p-6 mb-5 overflow-hidden relative">
    <div class="absolute inset-y-0 right-0 w-1/3 bg-gradient-to-l from-[#fff1ea] to-transparent pointer-events-none"></div>
    <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.24em] text-[#b7775b] font-semibold">Nuevo ingreso</p>
            <h3 class="mt-2 text-2xl font-bold text-slate-800">Registrar compra</h3>
            <p class="mt-2 text-sm text-slate-500 max-w-2xl">Selecciona el proveedor, agrega productos y confirma para incrementar stock automáticamente.</p>
        </div>
        <a href="{{ route('compras.index') }}" class="bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl font-semibold inline-flex items-center justify-center gap-2 hover:bg-slate-200">
            <i class="fas fa-arrow-left"></i>Volver
        </a>
    </div>
</div>

<form method="POST" action="{{ route('compras.store') }}" x-data="compraForm()" @submit="prepararSubmit">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="font-bold mb-4 flex items-center gap-2 text-slate-800"><i class="fas fa-file-invoice text-[#b7775b]"></i>Datos de la compra</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Proveedor *</label>
                        <select name="proveedor_id" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                            <option value="">— Seleccionar —</option>
                            @foreach($proveedores as $p)<option value="{{ $p->id }}">{{ $p->razon_social }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">N° Factura</label>
                        <input type="text" name="numero_factura" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Fecha</label>
                        <input type="date" name="fecha_compra" value="{{ now()->toDateString() }}" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Forma de pago</label>
                        <select name="forma_pago" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg">
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="credito">Crédito</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <h3 class="font-bold flex items-center gap-2 text-slate-800"><i class="fas fa-boxes-stacked text-[#b7775b]"></i>Productos</h3>
                    <span class="text-xs text-slate-500">Agrega uno o más productos recibidos</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-2 mb-4 bg-slate-50 border border-slate-200 rounded-2xl p-3">
                    <select x-model="nuevoItem.producto_id" class="md:col-span-5 px-3 py-2 border border-slate-300 rounded-lg">
                        <option value="">— Producto —</option>
                        @foreach($productos as $p)
                            <option value="{{ $p->id }}" data-precio="{{ $p->precio_compra }}" data-nombre="{{ $p->nombre }}" data-codigo="{{ $p->codigo }}">{{ $p->codigo }} - {{ $p->nombre }}</option>
                        @endforeach
                    </select>
                    <input type="number" step="0.001" x-model.number="nuevoItem.cantidad" placeholder="Cantidad" class="md:col-span-2 px-3 py-2 border border-slate-300 rounded-lg">
                    <input type="number" step="0.01" x-model.number="nuevoItem.precio_unitario" placeholder="Precio" class="md:col-span-3 px-3 py-2 border border-slate-300 rounded-lg">
                    <button type="button" @click="agregar" class="md:col-span-2 gradient-primary text-white py-2 rounded-lg font-semibold"><i class="fas fa-plus mr-1"></i>Agregar</button>
                </div>

                <table class="w-full text-sm">
                    <thead class="text-xs uppercase text-slate-500 border-b">
                        <tr>
                            <th class="text-left py-2">Producto</th>
                            <th class="text-right py-2">Cantidad</th>
                            <th class="text-right py-2">Precio</th>
                            <th class="text-right py-2">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, idx) in items" :key="idx">
                            <tr class="border-b">
                                <td class="py-2" x-text="item.nombre"></td>
                                <td class="py-2 text-right" x-text="item.cantidad"></td>
                                <td class="py-2 text-right" x-text="`{{ $moneda }}${item.precio_unitario.toFixed(2)}`"></td>
                                <td class="py-2 text-right font-semibold" x-text="`{{ $moneda }}${(item.cantidad * item.precio_unitario).toFixed(2)}`"></td>
                                <td class="py-2 text-right"><button type="button" @click="items.splice(idx,1)" class="text-red-500"><i class="fas fa-times"></i></button></td>
                            </tr>
                        </template>
                        <tr x-show="items.length === 0"><td colspan="5" class="text-center py-6 text-slate-400">Sin productos agregados</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-2xl shadow-md p-6 sticky top-20">
                <h3 class="font-bold mb-3 flex items-center gap-2"><i class="fas fa-clipboard-check text-[#b7775b]"></i>Resumen</h3>
                <div class="space-y-2 text-sm pb-3 border-b">
                    <div class="flex justify-between"><span class="text-slate-600">Items:</span><span x-text="items.length"></span></div>
                    <div class="flex justify-between"><span class="text-slate-600">Cantidad:</span><span x-text="items.reduce((s,i) => s + parseFloat(i.cantidad), 0).toFixed(2)"></span></div>
                </div>
                <div class="flex justify-between text-2xl font-bold pt-3"><span>Total:</span><span class="text-emerald-600" x-text="`{{ $moneda }}${total.toFixed(2)}`"></span></div>
                <textarea name="observaciones" placeholder="Observaciones..." rows="2" class="w-full mt-3 px-3 py-2 border border-slate-300 rounded-lg text-sm"></textarea>
                <button type="submit" :disabled="items.length === 0" class="w-full mt-3 gradient-primary text-white py-3 rounded-lg font-semibold disabled:opacity-50">
                    <i class="fas fa-save mr-2"></i>Registrar Compra
                </button>
                <a href="{{ route('compras.index') }}" class="block text-center mt-2 py-3 text-slate-600 hover:bg-slate-100 rounded-lg">Cancelar</a>
                <input type="hidden" name="items_json" :value="JSON.stringify(items)">
                <template x-for="(item, idx) in items" :key="idx">
                    <div>
                        <input type="hidden" :name="`items[${idx}][producto_id]`" :value="item.producto_id">
                        <input type="hidden" :name="`items[${idx}][cantidad]`" :value="item.cantidad">
                        <input type="hidden" :name="`items[${idx}][precio_unitario]`" :value="item.precio_unitario">
                    </div>
                </template>
            </div>
        </div>
    </div>
</form>

@section('scripts')
<script>
function compraForm() {
    return {
        items: [],
        nuevoItem: { producto_id: '', cantidad: 1, precio_unitario: 0 },
        get total() { return this.items.reduce((s, i) => s + i.cantidad * i.precio_unitario, 0); },
        agregar() {
            if (!this.nuevoItem.producto_id || this.nuevoItem.cantidad <= 0) return alert('Complete los datos');
            const opt = document.querySelector(`option[value="${this.nuevoItem.producto_id}"]`);
            this.items.push({
                producto_id: this.nuevoItem.producto_id,
                nombre: opt.dataset.nombre,
                cantidad: this.nuevoItem.cantidad,
                precio_unitario: this.nuevoItem.precio_unitario || parseFloat(opt.dataset.precio),
            });
            this.nuevoItem = { producto_id: '', cantidad: 1, precio_unitario: 0 };
        },
        prepararSubmit(e) { if (this.items.length === 0) { e.preventDefault(); alert('Agregue al menos un producto'); } }
    }
}
</script>
@endsection
@endsection
