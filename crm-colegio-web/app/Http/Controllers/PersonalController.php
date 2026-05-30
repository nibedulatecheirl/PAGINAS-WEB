<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    public function index(Request $request)
    {
        $query = Personal::query();

        if ($request->filled('buscar')) {
            $q = $request->buscar;
            $query->where(fn($q2) =>
                $q2->where('nombres', 'like', "%$q%")
                   ->orWhere('apellidos', 'like', "%$q%")
                   ->orWhere('dni', 'like', "%$q%")
            );
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $personal = $query->latest()->paginate(15)->withQueryString();
        $personalParaEditar = $request->filled('editar_personal')
            ? Personal::find($request->editar_personal)
            : null;

        return view('staff.index', compact('personal', 'personalParaEditar'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'dni'          => 'required|string|max:20|unique:personal',
            'nombres'      => 'required|string|max:100',
            'apellidos'    => 'required|string|max:100',
            'tipo'         => 'required|in:docente,administrativo,directivo,auxiliar',
            'especialidad' => 'nullable|string|max:120',
            'telefono'     => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:150',
            'direccion'    => 'nullable|string|max:500',
            'fecha_ingreso'=> 'required|date',
            'salario'      => 'required|numeric|min:0',
            'estado'       => 'nullable|in:activo,inactivo,licencia',
        ]);
        $data['estado'] = $data['estado'] ?? 'activo';

        Personal::create($data);

        return redirect()->route('personal.index')
            ->with('success', 'Personal registrado correctamente.');
    }

    public function show(Personal $personal)
    {
        return view('staff.show', compact('personal'));
    }

    public function edit(Personal $personal)
    {
        return view('staff.edit', compact('personal'));
    }

    public function update(Request $request, Personal $personal)
    {
        $data = $request->validate([
            'dni'          => 'required|string|max:20|unique:personal,dni,' . $personal->id,
            'nombres'      => 'required|string|max:100',
            'apellidos'    => 'required|string|max:100',
            'tipo'         => 'required|in:docente,administrativo,directivo,auxiliar',
            'especialidad' => 'nullable|string|max:120',
            'telefono'     => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:150',
            'direccion'    => 'nullable|string|max:500',
            'fecha_ingreso'=> 'required|date',
            'salario'      => 'required|numeric|min:0',
            'estado'       => 'required|in:activo,inactivo,licencia',
        ]);

        $personal->update($data);

        return redirect()->route('personal.index')
            ->with('success', 'Personal actualizado correctamente.');
    }

    public function destroy(Personal $personal)
    {
        $personal->update(['estado' => 'inactivo']);
        return redirect()->route('personal.index')
            ->with('success', 'Personal desactivado.');
    }
}
