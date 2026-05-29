@extends('layouts.app')
@section('title', 'Punto de Venta')
@section('header', 'Punto de Venta')

@section('content')
@php $moneda = $empresaGlobal->moneda ?? 'S/'; @endphp

<div class="bg-white rounded-2xl shadow-md p-6 mb-5 overflow-hidden relative">
    <div class="absolute inset-y-0 right-0 w-1/3 bg-gradient-to-l from-[#fff1ea] to-transparent pointer-events-none"></div>
    <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.24em] text-[#b7775b] font-semibold">Nueva venta</p>
            <h3 class="mt-2 text-2xl font-bold text-slate-800">Venta de mostrador</h3>
            <p class="mt-2 text-sm text-slate-500 max-w-2xl">Busca productos, arma el carrito y cobra con el turno de caja abierto. Para pedidos web usa el módulo de Pedidos web.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('ventas.index') }}" class="bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl font-semibold inline-flex items-center gap-2 hover:bg-slate-200">
                <i class="fas fa-receipt"></i>Historial
            </a>
            <a href="{{ route('pedidos-web.index') }}" class="bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl font-semibold inline-flex items-center gap-2 hover:bg-slate-200">
                <i class="fas fa-bag-shopping"></i>Pedidos web
            </a>
        </div>
    </div>
</div>

<div x-data="pos()" x-init="init()" class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    <!-- Panel Productos -->
    <div class="lg:col-span-2 space-y-5">
        <!-- Búsqueda -->
        <div class="bg-white rounded-2xl shadow-md p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-slate-800 flex items-center gap-2"><i class="fas fa-magnifying-glass text-[#b7775b]"></i>Buscar producto</h3>
                <span class="text-xs text-slate-500">Enter agrega el primer resultado</span>
            </div>
            <div class="flex gap-3">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" x-model="busqueda" @input.debounce.300ms="buscarProductos()"
                           @keydown.enter.prevent="agregarPrimero()"
                           class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:border-emerald-500"
                           placeholder="Buscar producto por nombre, código o código de barras (Enter para agregar)" autofocus>
                </div>
                <button @click="busqueda = ''; productosFiltrados = productosDestacados" class="px-4 py-3 bg-slate-100 hover:bg-slate-200 rounded-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Categorías rápidas -->
        <div class="flex gap-2 overflow-x-auto pb-2">
            <button @click="filtrarCategoria(null)" :class="categoriaActiva === null ? 'bg-emerald-500 text-white' : 'bg-white text-slate-700'"
                    class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap shadow-sm">
                <i class="fas fa-th-large mr-1"></i>Todos
            </button>
            @foreach($categorias as $cat)
                <button @click="filtrarCategoria({{ $cat->id }})"
                        :class="categoriaActiva === {{ $cat->id }} ? 'text-white' : 'bg-white text-slate-700'"
                        :style="categoriaActiva === {{ $cat->id }} ? 'background: {{ $cat->color }}' : ''"
                        class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap shadow-sm">
                    <i class="fas fa-{{ $cat->icono }} mr-1"></i>{{ $cat->nombre }}
                </button>
            @endforeach
        </div>

        <!-- Grid Productos -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            <template x-for="p in productosFiltrados" :key="p.id">
                <button @click="agregarProducto(p)"
                        class="bg-white rounded-xl shadow-sm hover:shadow-md transition transform hover:-translate-y-1 p-3 text-left">
                    <div class="aspect-square bg-slate-100 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
                        <template x-if="p.imagen">
                            <img :src="`/uploads/productos/${p.imagen}`" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!p.imagen">
                            <i class="fas fa-box text-3xl text-slate-300"></i>
                        </template>
                    </div>
                    <p class="text-xs font-semibold text-slate-700 line-clamp-2 mb-1" x-text="p.nombre"></p>
                    <div class="flex justify-between items-end">
                        <span class="text-[10px] text-slate-400" x-text="`Stock: ${parseFloat(p.stock).toFixed(0)}`"></span>
                        <span class="text-emerald-600 font-bold" x-text="`{{ $moneda }}${parseFloat(p.precio_venta).toFixed(2)}`"></span>
                    </div>
                </button>
            </template>
            <div x-show="productosFiltrados.length === 0" class="col-span-full text-center py-12 text-slate-400">
                <i class="fas fa-search text-5xl mb-3"></i>
                <p>Escribe para buscar productos</p>
            </div>
        </div>
    </div>

    <!-- Panel Carrito -->
    <div class="bg-white rounded-2xl shadow-md flex flex-col" style="max-height: calc(100vh - 120px);">
        <div class="p-4 border-b border-slate-200 flex justify-between items-center gradient-primary text-white rounded-t-2xl">
            <div>
                <h3 class="font-bold flex items-center gap-2"><i class="fas fa-shopping-cart"></i>Carrito</h3>
                <p class="text-xs text-emerald-100">Turno #{{ $turnoActivo->id }} - {{ $turnoActivo->caja->nombre }}</p>
            </div>
            <button @click="vaciarCarrito()" class="text-white/80 hover:text-white text-sm">
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <!-- Cliente -->
        <div class="p-3 border-b border-slate-200 bg-slate-50">
            <select x-model="clienteId" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg">
                <option value="">Cliente Genérico</option>
                @foreach($clientes as $cl)
                    <option value="{{ $cl->id }}">{{ $cl->nombre_completo }}</option>
                @endforeach
            </select>
        </div>

        <!-- Items -->
        <div class="flex-1 overflow-y-auto p-3 space-y-2 min-h-32">
            <template x-for="(item, idx) in carrito" :key="item.producto_id">
                <div class="bg-slate-50 rounded-lg p-3">
                    <div class="flex justify-between items-start mb-2">
                        <p class="font-semibold text-sm text-slate-800 flex-1 pr-2" x-text="item.nombre"></p>
                        <button @click="quitarItem(idx)" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center bg-white border border-slate-300 rounded-lg overflow-hidden">
                            <button @click="cambiarCantidad(idx, -1)" class="px-3 py-1 hover:bg-slate-100"><i class="fas fa-minus text-xs"></i></button>
                            <input type="number" step="0.01" x-model.number="item.cantidad"
                                   @input="actualizarTotal()" class="w-16 text-center border-x border-slate-200 py-1 text-sm focus:outline-none">
                            <button @click="cambiarCantidad(idx, 1)" class="px-3 py-1 hover:bg-slate-100"><i class="fas fa-plus text-xs"></i></button>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500" x-text="`{{ $moneda }}${item.precio_unitario.toFixed(2)} c/u`"></p>
                            <p class="font-bold text-emerald-600" x-text="`{{ $moneda }}${(item.cantidad * item.precio_unitario).toFixed(2)}`"></p>
                        </div>
                    </div>
                </div>
            </template>
            <div x-show="carrito.length === 0" class="text-center py-8 text-slate-300">
                <i class="fas fa-shopping-basket text-5xl mb-2"></i>
                <p class="text-sm">Carrito vacío</p>
            </div>
        </div>

        <!-- Totales -->
        <div class="p-4 border-t border-slate-200 bg-slate-50 space-y-2">
            <div class="flex justify-between text-sm text-slate-600">
                <span>Subtotal:</span>
                <span x-text="`{{ $moneda }} ${subtotal.toFixed(2)}`"></span>
            </div>
            <div class="flex justify-between items-center text-sm text-slate-600">
                <span>Descuento:</span>
                <input type="number" x-model.number="descuento" @input="actualizarTotal()" min="0" step="0.01"
                       class="w-24 text-right px-2 py-1 border border-slate-300 rounded">
            </div>
            <div class="flex justify-between text-sm text-slate-600">
                <span>Impuesto ({{ $empresaGlobal->impuesto ?? 0 }}%):</span>
                <span x-text="`{{ $moneda }} ${impuesto.toFixed(2)}`"></span>
            </div>
            <div class="flex justify-between text-2xl font-bold text-emerald-600 pt-2 border-t border-slate-200">
                <span>TOTAL:</span>
                <span x-text="`{{ $moneda }} ${total.toFixed(2)}`"></span>
            </div>

            <button @click="abrirPago()" :disabled="carrito.length === 0"
                    class="w-full gradient-primary text-white py-3 rounded-xl font-bold text-lg shadow-md hover:shadow-lg transition disabled:opacity-50 disabled:cursor-not-allowed mt-3">
                <i class="fas fa-cash-register mr-2"></i>Cobrar
            </button>
        </div>
    </div>
</div>

<!-- Modal Pago -->
<div x-show="modalPago" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display:none;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6" @click.outside="modalPago = false">
        <h3 class="text-xl font-bold text-slate-800 mb-4"><i class="fas fa-money-bill-wave mr-2 text-emerald-500"></i>Procesar Pago</h3>

        <div class="bg-slate-100 rounded-xl p-4 mb-4 text-center">
            <p class="text-sm text-slate-600">Total a Pagar</p>
            <p class="text-4xl font-extrabold text-emerald-600" x-text="`{{ $moneda }} ${total.toFixed(2)}`"></p>
        </div>

        <label class="block text-sm font-semibold text-slate-700 mb-2">Forma de Pago</label>
        <div class="grid grid-cols-3 gap-2 mb-4">
            <button @click="formaPago = 'efectivo'" :class="formaPago === 'efectivo' ? 'gradient-primary text-white' : 'bg-slate-100'" class="py-3 rounded-lg text-sm font-semibold">
                <i class="fas fa-money-bill block text-xl mb-1"></i>Efectivo
            </button>
            <button @click="formaPago = 'tarjeta'" :class="formaPago === 'tarjeta' ? 'bg-blue-500 text-white' : 'bg-slate-100'" class="py-3 rounded-lg text-sm font-semibold">
                <i class="fas fa-credit-card block text-xl mb-1"></i>Tarjeta
            </button>
            <button @click="formaPago = 'transferencia'" :class="formaPago === 'transferencia' ? 'bg-purple-500 text-white' : 'bg-slate-100'" class="py-3 rounded-lg text-sm font-semibold">
                <i class="fas fa-mobile-alt block text-xl mb-1"></i>Transfer.
            </button>
        </div>

        <label class="block text-sm font-semibold text-slate-700 mb-1">Monto Recibido</label>
        <input type="number" step="0.01" x-model.number="montoRecibido" :min="total"
               class="w-full px-4 py-3 text-2xl font-bold text-center border border-slate-300 rounded-xl mb-3 focus:outline-none focus:border-emerald-500">

        <div class="grid grid-cols-4 gap-2 mb-4">
            <template x-for="m in [10, 20, 50, 100]" :key="m">
                <button @click="montoRecibido = m" class="py-2 bg-slate-100 hover:bg-slate-200 rounded text-sm font-semibold" x-text="m"></button>
            </template>
        </div>

        <div class="bg-emerald-50 rounded-xl p-3 mb-4 flex justify-between items-center">
            <span class="text-emerald-700 font-semibold">Cambio:</span>
            <span class="text-2xl font-bold text-emerald-600" x-text="`{{ $moneda }} ${cambio.toFixed(2)}`"></span>
        </div>

        <div class="flex gap-2">
            <button @click="modalPago = false" class="flex-1 py-3 bg-slate-200 hover:bg-slate-300 rounded-xl font-semibold">
                Cancelar
            </button>
            <button @click="procesarVenta()" :disabled="procesando || montoRecibido < total"
                    class="flex-1 py-3 gradient-primary text-white rounded-xl font-semibold disabled:opacity-50">
                <span x-show="!procesando"><i class="fas fa-check mr-2"></i>Confirmar Venta</span>
                <span x-show="procesando"><i class="fas fa-spinner fa-spin mr-2"></i>Procesando...</span>
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function pos() {
    return {
        productosDestacados: @json($productos),
        productosFiltrados: @json($productos),
        busqueda: '',
        categoriaActiva: null,
        carrito: [],
        clienteId: '',
        formaPago: 'efectivo',
        descuento: 0,
        montoRecibido: 0,
        modalPago: false,
        procesando: false,
        impuestoTasa: {{ $empresaGlobal->impuesto ?? 0 }},
        impuestoIncluido: {{ $empresaGlobal && $empresaGlobal->impuesto_incluido ? 'true' : 'false' }},

        init() {
            window.addEventListener('keydown', (e) => {
                if (e.key === 'F2') { e.preventDefault(); this.abrirPago(); }
                if (e.key === 'Escape') { this.modalPago = false; }
            });
        },

        get subtotal() {
            return this.carrito.reduce((sum, i) => sum + (i.cantidad * i.precio_unitario), 0);
        },
        get impuesto() {
            if (this.impuestoIncluido) {
                const base = (this.subtotal - this.descuento) / (1 + this.impuestoTasa/100);
                return (this.subtotal - this.descuento) - base;
            }
            return (this.subtotal - this.descuento) * (this.impuestoTasa/100);
        },
        get total() {
            return this.impuestoIncluido
                ? this.subtotal - this.descuento
                : this.subtotal - this.descuento + this.impuesto;
        },
        get cambio() {
            return Math.max(0, this.montoRecibido - this.total);
        },

        async buscarProductos() {
            if (!this.busqueda || this.busqueda.length < 2) {
                this.productosFiltrados = this.productosDestacados;
                return;
            }
            try {
                const res = await fetch(`/api/productos/buscar?q=${encodeURIComponent(this.busqueda)}`);
                this.productosFiltrados = await res.json();
            } catch(e) { console.error(e); }
        },

        filtrarCategoria(catId) {
            this.categoriaActiva = catId;
            if (!catId) {
                this.productosFiltrados = this.productosDestacados;
            } else {
                this.productosFiltrados = this.productosDestacados.filter(p => p.categoria_id === catId);
            }
        },

        agregarPrimero() {
            if (this.productosFiltrados.length > 0) this.agregarProducto(this.productosFiltrados[0]);
        },

        agregarProducto(p) {
            const existing = this.carrito.find(i => i.producto_id === p.id);
            if (existing) {
                existing.cantidad++;
            } else {
                this.carrito.push({
                    producto_id: p.id,
                    codigo: p.codigo,
                    nombre: p.nombre,
                    cantidad: 1,
                    precio_unitario: parseFloat(p.precio_venta),
                    stock: parseFloat(p.stock),
                });
            }
            this.busqueda = '';
            this.actualizarTotal();
        },

        cambiarCantidad(idx, delta) {
            this.carrito[idx].cantidad += delta;
            if (this.carrito[idx].cantidad <= 0) this.quitarItem(idx);
        },

        quitarItem(idx) { this.carrito.splice(idx, 1); },
        vaciarCarrito() { if (confirm('¿Vaciar el carrito?')) this.carrito = []; },
        actualizarTotal() {},

        abrirPago() {
            if (this.carrito.length === 0) return;
            this.montoRecibido = this.total;
            this.modalPago = true;
        },

        async procesarVenta() {
            if (this.procesando) return;
            this.procesando = true;

            const data = {
                cliente_id: this.clienteId || null,
                forma_pago: this.formaPago,
                monto_recibido: this.montoRecibido,
                descuento: this.descuento,
                items: this.carrito.map(i => ({
                    producto_id: i.producto_id,
                    cantidad: i.cantidad,
                    precio_unitario: i.precio_unitario,
                })),
            };

            try {
                const res = await fetch('{{ route("ventas.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data),
                });
                const result = await res.json();

                if (result.success) {
                    window.open(result.redirect, '_blank');
                    this.carrito = [];
                    this.descuento = 0;
                    this.montoRecibido = 0;
                    this.modalPago = false;
                    alert('¡Venta registrada! Ticket: ' + result.numero_ticket);
                } else {
                    alert('Error: ' + (result.error || 'Error desconocido'));
                }
            } catch(e) {
                alert('Error al procesar: ' + e.message);
            } finally {
                this.procesando = false;
            }
        }
    }
}
</script>
@endsection
