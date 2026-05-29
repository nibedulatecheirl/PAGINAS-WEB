@extends('layouts.app')
@section('title', 'Roles')
@section('header', 'Roles y Permisos')

@section('content')
<div x-data="rolesPage({ storeUrl: {{ \Illuminate\Support\Js::from(route('roles.store')) }} })">
    <div class="bg-white rounded-2xl shadow-md p-5 mb-5 flex flex-col sm:flex-row gap-3 sm:justify-between sm:items-center">
        <div>
            <h3 class="font-bold">Roles del Sistema</h3>
            <p class="text-sm text-slate-500">Define accesos por modulo para usuarios administrativos.</p>
        </div>
        <button type="button" @click="openCreate()" class="gradient-primary text-white px-5 py-2.5 rounded-lg font-semibold flex items-center justify-center gap-2"><i class="fas fa-plus"></i>Nuevo Rol</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($roles as $r)
            @php
                $permisosRol = $r->permisos ?? [];
                $rolProtegido = $r->nombre === 'Administrador';
                $rolPayload = [
                    'id' => $r->id,
                    'update_url' => route('roles.update', $r),
                    'nombre' => $r->nombre,
                    'descripcion' => $r->descripcion,
                    'permisos' => array_values($permisosRol),
                    'activo' => (bool) $r->activo,
                    'protegido' => $rolProtegido,
                ];
            @endphp
            <div class="bg-white rounded-2xl shadow-md p-5">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-tag text-purple-600 text-xl"></i>
                    </div>
                    <button type="button" @click="openEditor({{ \Illuminate\Support\Js::from($rolPayload) }})" class="p-2 hover:bg-yellow-50 text-yellow-600 rounded-lg"><i class="fas fa-edit"></i></button>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-bold">{{ $r->nombre }}</h3>
                    @if($rolProtegido)
                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">Sistema</span>
                    @endif
                    @unless($r->activo)
                        <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Inactivo</span>
                    @endunless
                </div>
                <p class="text-sm text-slate-500 mb-3">{{ $r->descripcion ?? 'Sin descripcion' }}</p>
                <div class="flex flex-wrap gap-1 mb-3">
                    @if(in_array('*', $permisosRol, true))
                        <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-semibold">Acceso total</span>
                    @else
                        @forelse($permisosRol as $p)
                            <span class="bg-slate-100 px-2 py-1 rounded text-xs">{{ $permisosDisponibles[$p] ?? $p }}</span>
                        @empty
                            <span class="bg-slate-100 px-2 py-1 rounded text-xs text-slate-500">Sin permisos</span>
                        @endforelse
                    @endif
                </div>
                <p class="text-xs text-slate-500"><i class="fas fa-users mr-1"></i>{{ $r->users_count }} usuario(s)</p>
            </div>
        @endforeach
    </div>

    <div x-show="open" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display:none;">
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 max-h-[92vh] overflow-y-auto" @click.outside="open=false">
            <h3 class="text-xl font-bold mb-4" x-text="form.id ? 'Editar Rol' : 'Nuevo Rol'"></h3>
            <form :action="form.id ? form.update_url : storeUrl" method="POST" class="space-y-3">
                @csrf
                <template x-if="form.id"><input type="hidden" name="_method" value="PUT"></template>

                <div>
                    <label class="text-sm font-semibold">Nombre del rol</label>
                    <input x-ref="nombre" name="nombre" x-model="form.nombre" required :readonly="form.protegido" class="w-full px-3 py-2 border border-slate-300 rounded-lg read-only:bg-slate-100 read-only:text-slate-500">
                    <p x-show="form.protegido" class="text-xs text-slate-500 mt-1">El rol Administrador esta protegido para evitar perder acceso al sistema.</p>
                </div>
                <div>
                    <label class="text-sm font-semibold">Descripcion</label>
                    <input name="descripcion" x-model="form.descripcion" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="text-sm font-semibold mb-2 block">Permisos</label>
                    <template x-if="form.protegido">
                        <div class="bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg px-3 py-2 text-sm">
                            Acceso total protegido.
                        </div>
                    </template>
                    <div x-show="!form.protegido" class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-64 overflow-y-auto">
                        @foreach($permisosDisponibles as $key => $label)
                            <label class="flex items-center gap-2 p-2 hover:bg-slate-50 rounded">
                                <input type="checkbox" name="permisos[]" value="{{ $key }}" x-model="form.permisos" class="rounded text-emerald-500">
                                <span class="text-sm">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <template x-if="form.id && !form.protegido">
                    <label class="flex gap-2 items-center">
                        <input type="checkbox" name="activo" value="1" x-model="form.activo" class="rounded text-emerald-500">
                        <span>Activo</span>
                    </label>
                </template>
                <template x-if="form.protegido"><input type="hidden" name="activo" value="1"></template>
                <div class="flex gap-2 pt-3">
                    <button type="button" @click="open=false" class="flex-1 py-2.5 bg-slate-200 rounded-lg">Cancelar</button>
                    <button type="submit" class="flex-1 py-2.5 gradient-primary text-white rounded-lg font-semibold">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function rolesPage(config) {
        const emptyForm = () => ({
            id: null,
            update_url: '',
            nombre: '',
            descripcion: '',
            permisos: [],
            activo: true,
            protegido: false,
        });

        return {
            open: false,
            storeUrl: config.storeUrl,
            form: emptyForm(),
            openCreate() {
                this.form = emptyForm();
                this.open = true;
                this.$nextTick(() => this.$refs.nombre?.focus());
            },
            openEditor(rol) {
                this.form = {
                    ...emptyForm(),
                    ...rol,
                    descripcion: rol.descripcion || '',
                    permisos: Array.isArray(rol.permisos) ? rol.permisos : [],
                    activo: Boolean(rol.activo),
                    protegido: Boolean(rol.protegido),
                };
                this.open = true;
                this.$nextTick(() => this.$refs.nombre?.focus());
            },
        };
    }
</script>
@endsection
