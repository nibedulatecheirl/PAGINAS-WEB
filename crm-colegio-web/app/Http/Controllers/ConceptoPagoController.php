<?php

namespace App\Http\Controllers;

use App\Models\ConceptoPago;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConceptoPagoController extends Controller
{
    public function index()
    {
        $conceptos = ConceptoPago::withCount('pagos')->latest()->get();
        return view('conceptos.index', compact('conceptos'));
    }

    public function create()
    {
        return view('conceptos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:conceptos_pago,nombre',
            'descripcion' => 'nullable|string|max:500',
            'monto'  => 'required|numeric|min:0',
            'tipo'   => 'required|in:mensualidad,matricula,taller,otros',
        ]);

        $data = $request->only(['nombre', 'descripcion', 'monto', 'tipo']);
        $data['activo'] = $request->boolean('activo');
        ConceptoPago::create($data);

        return redirect()->route('conceptos.index')
            ->with('success', 'Concepto de pago creado correctamente.');
    }

    public function edit(ConceptoPago $concepto)
    {
        return view('conceptos.edit', compact('concepto'));
    }

    public function update(Request $request, ConceptoPago $concepto)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('conceptos_pago', 'nombre')->ignore($concepto->id)],
            'descripcion' => 'nullable|string|max:500',
            'monto'  => 'required|numeric|min:0',
            'tipo'   => 'required|in:mensualidad,matricula,taller,otros',
        ]);

        $data = $request->only(['nombre', 'descripcion', 'monto', 'tipo']);
        $data['activo'] = $request->boolean('activo');
        $concepto->update($data);

        return redirect()->route('conceptos.index')
            ->with('success', 'Concepto actualizado correctamente.');
    }

    public function destroy(ConceptoPago $concepto)
    {
        // Solo desactivar, no eliminar si tiene pagos
        if ($concepto->pagos()->count() > 0) {
            $concepto->update(['activo' => false]);
            return redirect()->route('conceptos.index')
                ->with('success', 'Concepto desactivado (tiene pagos asociados).');
        }
        $concepto->delete();
        return redirect()->route('conceptos.index')
            ->with('success', 'Concepto eliminado.');
    }

    public function toggleActivo(ConceptoPago $concepto)
    {
        $concepto->update(['activo' => !$concepto->activo]);
        $estado = $concepto->activo ? 'activado' : 'desactivado';
        return redirect()->route('conceptos.index')
            ->with('success', "Concepto $estado correctamente.");
    }
}
