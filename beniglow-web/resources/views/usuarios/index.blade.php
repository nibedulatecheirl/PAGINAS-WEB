@extends('layouts.app')
@section('title', 'Usuarios')
@section('header', 'Gestion de Usuarios')

@section('content')
<div x-data="usuariosPage({ storeUrl: {{ \Illuminate\Support\Js::from(route('usuarios.store')) }} })">
    <div class="bg-white rounded-2xl shadow-md p-5 mb-5 flex flex-col lg:flex-row gap-3 lg:items-center lg:justify-between">
        <form method="GET" class="flex-1 flex gap-2 max-w-2xl">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input name="buscar" value="{{ request('buscar') }}" placeholder="Buscar usuario..." class="w-full pl-12 pr-4 py-2.5 border border-slate-300 rounded-lg">
            </div>
            <button class="bg-slate-800 text-white px-4 py-2.5 rounded-lg"><i class="fas fa-search"></i></button>
        </form>
        <div class="flex gap-2">
            <a href="{{ route('usuarios.roles') }}" class="bg-blue-100 text-blue-700 px-4 py-2.5 rounded-lg font-semibold flex items-center gap-2"><i class="fas fa-user-tag"></i>Roles</a>
            <button type="button" @click="openCreate()" class="gradient-primary text-white px-5 py-2.5 rounded-lg font-semibold flex items-center gap-2"><i class="fas fa-user-plus"></i>Nuevo Usuario</button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($usuarios as $u)
            @php
                $usuarioPayload = [
                    'id' => $u->id,
                    'update_url' => route('usuarios.update', $u),
                    'name' => $u->name,
                    'username' => $u->username,
                    'email' => $u->email,
                    'telefono' => $u->telefono,
                    'role_id' => (string) $u->role_id,
                    'activo' => (bool) $u->activo,
                    'is_current' => $u->id === auth()->id(),
                ];
            @endphp
            <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-lg transition">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 gradient-primary rounded-full flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold truncate">{{ $u->name }}</h3>
                        <p class="text-sm text-slate-500">{{ '@' . $u->username }}</p>
                        @if($u->role)
                            <span class="inline-block mt-2 px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">{{ $u->role->nombre }}</span>
                        @endif
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 text-sm space-y-1">
                    <p><i class="fas fa-envelope text-slate-400 w-4"></i> {{ $u->email }}</p>
                    @if($u->telefono)<p><i class="fas fa-phone text-slate-400 w-4"></i> {{ $u->telefono }}</p>@endif
                    <p class="text-xs text-slate-500">
                        {!! $u->activo ? '<span class="text-green-600"><i class="fas fa-circle text-[8px]"></i> Activo</span>' : '<span class="text-red-600"><i class="fas fa-circle text-[8px]"></i> Inactivo</span>' !!}
                    </p>
                </div>
                <div class="flex gap-2 mt-3">
                    <button type="button" @click="openEditor({{ \Illuminate\Support\Js::from($usuarioPayload) }})" class="flex-1 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg text-sm"><i class="fas fa-edit mr-1"></i>Editar</button>
                    <form method="POST" action="{{ route('usuarios.destroy', $u) }}" class="flex-1" onsubmit="return confirm('Deseas desactivar este usuario?')">
                        @csrf
                        @method('DELETE')
                        <button class="w-full py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm" @disabled($u->id === auth()->id())><i class="fas fa-user-slash mr-1"></i>Desactivar</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-slate-400 py-12">Sin usuarios</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $usuarios->withQueryString()->links() }}</div>

    <div x-show="open" x-cloak class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display:none;">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 max-h-[92vh] overflow-y-auto" @click.outside="open=false">
            <h3 class="text-xl font-bold mb-4" x-text="form.id ? 'Editar Usuario' : 'Nuevo Usuario'"></h3>
            <form :action="form.id ? form.update_url : storeUrl" method="POST" class="space-y-3">
                @csrf
                <template x-if="form.id"><input type="hidden" name="_method" value="PUT"></template>

                <div>
                    <label class="text-sm font-semibold">Nombre completo</label>
                    <input x-ref="name" name="name" x-model="form.name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div>
                        <label class="text-sm font-semibold">Usuario</label>
                        <input name="username" x-model="form.username" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="text-sm font-semibold">Email</label>
                        <input name="email" type="email" x-model="form.email" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-semibold">Telefono</label>
                    <input name="telefono" x-model="form.telefono" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="text-sm font-semibold">Rol</label>
                    <template x-if="form.is_current"><input type="hidden" name="role_id" :value="form.role_id"></template>
                    <select name="role_id" x-model="form.role_id" required :disabled="form.is_current" class="w-full px-3 py-2 border border-slate-300 rounded-lg disabled:bg-slate-100 disabled:text-slate-500">
                        <option value="">Seleccionar rol</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                        @endforeach
                    </select>
                    <p x-show="form.is_current" class="text-xs text-slate-500 mt-1">Tu propio rol no se cambia desde este formulario para evitar perder acceso.</p>
                </div>
                <div>
                    <label class="text-sm font-semibold" x-text="form.id ? 'Nueva contrasena (opcional)' : 'Contrasena'"></label>
                    <input type="password" name="password" x-model="form.password" :required="!form.id" autocomplete="new-password" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    <p class="text-xs text-slate-500 mt-1">Minimo 8 caracteres, con letras y numeros.</p>
                </div>
                <div>
                    <label class="text-sm font-semibold">Confirmar contrasena</label>
                    <input type="password" name="password_confirmation" x-model="form.password_confirmation" :required="!form.id || form.password.length > 0" autocomplete="new-password" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                </div>
                <template x-if="form.id && !form.is_current">
                    <label class="flex gap-2 items-center">
                        <input type="checkbox" name="activo" value="1" x-model="form.activo" class="rounded text-emerald-500">
                        <span>Activo</span>
                    </label>
                </template>
                <template x-if="form.is_current"><input type="hidden" name="activo" value="1"></template>
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
    function usuariosPage(config) {
        const emptyForm = () => ({
            id: null,
            update_url: '',
            name: '',
            username: '',
            email: '',
            telefono: '',
            role_id: '',
            password: '',
            password_confirmation: '',
            activo: true,
            is_current: false,
        });

        return {
            open: false,
            storeUrl: config.storeUrl,
            form: emptyForm(),
            openCreate() {
                this.form = emptyForm();
                this.open = true;
                this.$nextTick(() => this.$refs.name?.focus());
            },
            openEditor(usuario) {
                this.form = {
                    ...emptyForm(),
                    ...usuario,
                    role_id: usuario.role_id ? String(usuario.role_id) : '',
                    activo: Boolean(usuario.activo),
                    is_current: Boolean(usuario.is_current),
                    password: '',
                    password_confirmation: '',
                };
                this.open = true;
                this.$nextTick(() => this.$refs.name?.focus());
            },
        };
    }
</script>
@endsection
