<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Role;

class UsuarioController extends Controller
{
    private const PERMISOS_DISPONIBLES = [
        'productos' => 'Productos',
        'ventas' => 'Ventas',
        'compras' => 'Compras',
        'clientes' => 'Clientes',
        'proveedores' => 'Proveedores',
        'caja' => 'Caja',
        'reportes' => 'Reportes',
        'promociones' => 'Promociones',
        'pedidos-web' => 'Pedidos web',
        'ecommerce' => 'Panel e-commerce',
        'configuracion' => 'Configuracion',
        'usuarios' => 'Usuarios',
        'backup' => 'Backup',
    ];

    public function index(Request $request)
    {
        $query = User::with('role');
        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function($q) use ($b) {
                $q->where('name', 'LIKE', "%$b%")
                  ->orWhere('username', 'LIKE', "%$b%")
                  ->orWhere('email', 'LIKE', "%$b%");
            });
        }
        $usuarios = $query->orderBy('name')->paginate(15);
        $roles = Role::where('activo', true)->get();
        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where('activo', true),
            ],
            'telefono' => 'nullable|string|max:30',
        ]);

        $data['activo'] = true;
        User::create($data);
        return back()->with('success', 'Usuario creado correctamente');
    }

    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $usuario->id,
            'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where('activo', true),
            ],
            'telefono' => 'nullable|string|max:30',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($usuario->id === auth()->id()) {
            $data['role_id'] = $usuario->role_id;
            $data['activo'] = true;
        } else {
            $data['activo'] = $request->boolean('activo', true);
        }

        $usuario->update($data);
        return back()->with('success', 'Usuario actualizado');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puede eliminar su propia cuenta');
        }
        $usuario->update(['activo' => false]);
        return back()->with('success', 'Usuario desactivado');
    }

    public function roles()
    {
        $roles = Role::withCount('users')->orderBy('nombre')->get();
        $permisosDisponibles = self::PERMISOS_DISPONIBLES;

        return view('usuarios.roles', compact('roles', 'permisosDisponibles'));
    }

    public function storeRol(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:80|unique:roles,nombre',
            'descripcion' => 'nullable|string|max:255',
            'permisos' => 'nullable|array',
            'permisos.*' => ['string', Rule::in(array_keys(self::PERMISOS_DISPONIBLES))],
        ]);

        $data['permisos'] = $this->normalizarPermisos($data['permisos'] ?? []);
        $data['activo'] = true;
        Role::create($data);
        return back()->with('success', 'Rol creado correctamente');
    }

    public function updateRol(Request $request, Role $rol)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:80|unique:roles,nombre,' . $rol->id,
            'descripcion' => 'nullable|string|max:255',
            'permisos' => 'nullable|array',
            'permisos.*' => ['string', Rule::in(array_keys(self::PERMISOS_DISPONIBLES))],
        ]);

        if ($rol->nombre === 'Administrador') {
            $data['nombre'] = 'Administrador';
            $data['permisos'] = ['*'];
            $data['activo'] = true;
        } else {
            $data['permisos'] = $this->normalizarPermisos($data['permisos'] ?? []);
            $data['activo'] = $request->boolean('activo', true);
        }

        $rol->update($data);
        return back()->with('success', 'Rol actualizado');
    }

    private function normalizarPermisos(array $permisos): array
    {
        $permitidos = array_keys(self::PERMISOS_DISPONIBLES);

        return collect($permisos)
            ->filter(fn ($permiso) => in_array($permiso, $permitidos, true))
            ->unique()
            ->values()
            ->all();
    }
}
