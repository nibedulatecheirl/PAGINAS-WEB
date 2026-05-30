<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Alumno;
use App\Models\ConceptoPago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pago::with(['alumno', 'concepto']);

        if ($request->filled('buscar')) {
            $q = $request->buscar;
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('alumno', fn($a) =>
                    $a->where('nombres', 'like', "%$q%")
                      ->orWhere('apellidos', 'like', "%$q%")
                      ->orWhere('dni', 'like', "%$q%")
                )->orWhere('numero_recibo', 'like', "%$q%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('mes')) {
            $query->where('mes', $request->mes);
        }

        $pagos    = $query->latest()->paginate(15)->withQueryString();
        $alumnos  = Alumno::where('estado', 'activo')
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->get();
        $conceptos = ConceptoPago::activos()->get();

        $resumen = [
            'total_pagado'   => Pago::where('estado', 'pagado')->whereMonth('fecha_pago', date('m'))->sum('monto_pagado'),
            'total_pendiente'=> Pago::where('estado', 'pendiente')->sum('monto'),
            'total_vencido'  => Pago::where('estado', 'vencido')->sum('monto'),
        ];

        $pagoParaEditar = $request->filled('editar_pago')
            ? Pago::with(['alumno', 'concepto'])->find($request->editar_pago)
            : null;

        return view('payments.index', compact('pagos', 'alumnos', 'conceptos', 'resumen', 'pagoParaEditar'));
    }

    public function create()
    {
        $alumnos  = Alumno::where('estado', 'activo')->get();
        $conceptos = ConceptoPago::activos()->get();
        return view('payments.create', compact('alumnos', 'conceptos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alumno_id'  => 'required|exists:alumnos,id',
            'concepto_id'=> 'required|exists:conceptos_pago,id',
            'anio_escolar'=> 'required|integer',
            'mes'        => 'nullable|integer|min:1|max:12',
            'monto'      => 'required|numeric|min:0',
            'descuento'  => 'nullable|numeric|min:0',
            'monto_pagado'=> 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'fecha_vencimiento' => 'nullable|date',
            'metodo_pago'=> 'required|in:efectivo,transferencia,tarjeta,cheque',
            'estado'     => 'nullable|in:pagado,pendiente,vencido,anulado',
            'observaciones' => 'nullable|string',
        ]);

        $data = $request->only([
            'alumno_id', 'concepto_id', 'anio_escolar', 'mes', 'monto', 'descuento',
            'monto_pagado', 'fecha_pago', 'fecha_vencimiento', 'metodo_pago',
            'estado', 'observaciones',
        ]);
        $data['descuento'] = $data['descuento'] ?? 0;
        $neto = max(0, (float) $data['monto'] - (float) $data['descuento']);
        if ((float) $data['monto_pagado'] > $neto) {
            return back()->withInput()->with('error', 'El monto pagado no puede superar el monto neto del concepto.');
        }

        $data['numero_recibo']  = Pago::generarNumeroRecibo();
        $data['registrado_por'] = auth()->id();
        $data['estado']         = $data['estado'] ?? ((float) $data['monto_pagado'] >= $neto ? 'pagado' : 'pendiente');

        Pago::create($data);

        return redirect()->route('pagos.index')
            ->with('success', 'Pago registrado. Recibo: ' . $data['numero_recibo']);
    }

    public function show(Pago $pago)
    {
        $pago->load(['alumno', 'concepto', 'registradoPor']);
        return view('payments.show', compact('pago'));
    }

    public function edit(Pago $pago)
    {
        $alumnos  = Alumno::where('estado', 'activo')->get();
        $conceptos = ConceptoPago::activos()->get();
        return view('payments.edit', compact('pago', 'alumnos', 'conceptos'));
    }

    public function update(Request $request, Pago $pago)
    {
        $request->validate([
            'estado' => 'required|in:pagado,pendiente,vencido,anulado',
            'monto' => 'required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
            'monto_pagado' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'fecha_vencimiento' => 'nullable|date',
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta,cheque',
            'observaciones' => 'nullable|string',
        ]);

        $data = $request->only([
            'monto', 'descuento', 'monto_pagado', 'fecha_pago', 'fecha_vencimiento',
            'metodo_pago', 'estado', 'observaciones',
        ]);
        $data['descuento'] = $data['descuento'] ?? 0;
        $neto = max(0, (float) $data['monto'] - (float) $data['descuento']);
        if ((float) $data['monto_pagado'] > $neto) {
            return back()->withInput()->with('error', 'El monto pagado no puede superar el monto neto del concepto.');
        }

        $pago->update($data);

        return redirect()->route('pagos.index')
            ->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(Pago $pago)
    {
        $pago->update(['estado' => 'anulado']);
        return redirect()->route('pagos.index')
            ->with('success', 'Pago anulado.');
    }
}
