@extends('layouts.app')
@section('title', 'Editar Pago')
@section('page-title', 'Editar Pago')

@section('content')
<div style="max-width:760px;">
<div style="display:flex;gap:12px;margin-bottom:16px;">
    <a href="{{ route('pagos.show', $pago) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
</div>

<form method="POST" action="{{ route('pagos.update', $pago) }}">
@csrf @method('PUT')
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-edit" style="color:#4f86bd;margin-right:8px;"></i>Editar Pago — {{ $pago->numero_recibo }}</span>
    </div>
    <div class="card-body">
        {{-- Solo lectura --}}
        <div class="grid grid-2" style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--border);">
            <div>
                <div style="font-size:11px;color:var(--muted);">Alumno</div>
                <div style="font-weight:700;">{{ $pago->alumno->nombre_completo ?? '—' }}</div>
            </div>
            <div>
                <div style="font-size:11px;color:var(--muted);">Concepto</div>
                <div style="font-weight:700;">{{ $pago->concepto->nombre ?? '—' }}</div>
            </div>
        </div>

        <div class="grid grid-4">
            <div class="form-group">
                <label class="form-label">Monto Original (S/.)</label>
                <input type="number" name="monto" id="monto" class="form-control" step="0.01" min="0"
                    value="{{ old('monto', $pago->monto) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Descuento (S/.)</label>
                <input type="number" name="descuento" id="descuento" class="form-control" step="0.01" min="0"
                    value="{{ old('descuento', $pago->descuento) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Monto Pagado (S/.) *</label>
                <input type="number" name="monto_pagado" id="monto_pagado" class="form-control" step="0.01" min="0"
                    value="{{ old('monto_pagado', $pago->monto_pagado) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Estado *</label>
                <select name="estado" class="form-control" required>
                    @foreach(['pagado','pendiente','vencido','anulado'] as $e)
                    <option value="{{ $e }}" {{ old('estado',$pago->estado)===$e ? 'selected':'' }}>{{ ucfirst($e) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-2">
            <div class="form-group">
                <label class="form-label">Fecha de Pago *</label>
                <input type="date" name="fecha_pago" class="form-control"
                    value="{{ old('fecha_pago', $pago->fecha_pago?->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Método de Pago</label>
                <select name="metodo_pago" class="form-control">
                    @foreach(['efectivo','transferencia','tarjeta','cheque'] as $m)
                    <option value="{{ $m }}" {{ old('metodo_pago',$pago->metodo_pago)===$m ? 'selected':'' }}>{{ ucfirst($m) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Fecha de Vencimiento</label>
                <input type="date" name="fecha_vencimiento" class="form-control"
                    value="{{ old('fecha_vencimiento', $pago->fecha_vencimiento?->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $pago->observaciones) }}</textarea>
        </div>
    </div>
</div>
<div style="display:flex;gap:12px;justify-content:flex-end;margin-top:16px;">
    <a href="{{ route('pagos.show', $pago) }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
</div>
</form>
</div>
@endsection

@push('scripts')
<script>
function actualizarSaldoEdit() {
    const m = parseFloat(document.getElementById('monto').value) || 0;
    const d = parseFloat(document.getElementById('descuento').value) || 0;
    const p = parseFloat(document.getElementById('monto_pagado').value) || 0;
    if (p > Math.max(0, m - d)) {
        document.getElementById('monto_pagado').setCustomValidity('El monto pagado no puede superar el monto neto.');
    } else {
        document.getElementById('monto_pagado').setCustomValidity('');
    }
}
['monto','descuento','monto_pagado'].forEach(id => document.getElementById(id).addEventListener('input', actualizarSaldoEdit));
actualizarSaldoEdit();
</script>
@endpush

