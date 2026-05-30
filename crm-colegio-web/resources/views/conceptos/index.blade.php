@extends('layouts.app')
@section('title', 'Conceptos de Pago')
@section('page-title', 'Conceptos de Pago')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <p style="color:var(--muted);font-size:13px;">{{ $conceptos->count() }} conceptos registrados</p>
    <button type="button" onclick="abrirConceptoModal()" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo Concepto</button>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                    <th>N° de Pagos</th>
                    <th>Estado</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($conceptos as $c)
                @php
                    $conceptoData = [
                        'action' => route('conceptos.update', $c),
                        'id' => $c->id,
                        'nombre' => $c->nombre,
                        'descripcion' => $c->descripcion,
                        'monto' => (float) $c->monto,
                        'tipo' => $c->tipo,
                        'activo' => $c->activo,
                        'pagos_count' => $c->pagos_count,
                    ];
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $c->nombre }}</div>
                        @if($c->descripcion)
                        <div style="font-size:11px;color:var(--muted);">{{ Str::limit($c->descripcion, 60) }}</div>
                        @endif
                    </td>
                    <td>
                        @php $tipoBadge = match($c->tipo) { 'mensualidad'=>'badge-primary','matricula'=>'badge-info','taller'=>'badge-warning','otros'=>'badge-secondary',default=>'badge-secondary' }; @endphp
                        <span class="badge {{ $tipoBadge }}">{{ ucfirst($c->tipo) }}</span>
                    </td>
                    <td style="font-weight:700;font-size:15px;color:var(--primary);">S/. {{ number_format($c->monto, 2) }}</td>
                    <td>
                        <span style="font-size:13px;">{{ number_format($c->pagos_count) }} pagos</span>
                    </td>
                    <td>
                        <span class="badge {{ $c->activo ? 'badge-success' : 'badge-secondary' }}">
                            {{ $c->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <button type="button"
                                data-concepto-action="ver"
                                data-concepto='@json($conceptoData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                class="btn btn-sm btn-secondary btn-icon" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button"
                                data-concepto-action="editar"
                                data-concepto='@json($conceptoData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                class="btn btn-sm btn-secondary btn-icon" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('conceptos.toggle', $c) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $c->activo ? 'btn-danger' : 'btn-success' }} btn-icon"
                                    title="{{ $c->activo ? 'Desactivar' : 'Activar' }}">
                                    <i class="fas fa-{{ $c->activo ? 'toggle-off' : 'toggle-on' }}"></i>
                                </button>
                            </form>
                            @if($c->pagos_count == 0)
                            <form method="POST" action="{{ route('conceptos.destroy', $c) }}"
                                onsubmit="return confirm('¿Eliminar este concepto?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:48px;color:var(--muted);">
                        <i class="fas fa-tags" style="font-size:36px;opacity:.3;display:block;margin-bottom:12px;"></i>
                        No hay conceptos de pago registrados.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modal-concepto-detalle" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(560px,94vw);padding:0;">
        <div class="card-header">
            <div>
                <span class="card-title"><i class="fas fa-tag" style="color:var(--primary);"></i> Detalle del Concepto</span>
                <div id="concepto-detalle-tipo" style="font-size:12px;color:var(--muted);margin-top:2px;"></div>
            </div>
            <button type="button" onclick="cerrarConceptoDetalleModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <div style="padding:14px;border:1px solid var(--border);border-radius:12px;background:#f7fafd;margin-bottom:14px;">
                <div style="font-size:12px;color:var(--muted);font-weight:700;">Concepto</div>
                <div id="concepto-detalle-nombre" style="font-size:18px;font-weight:800;margin-top:3px;"></div>
                <div id="concepto-detalle-descripcion" style="font-size:13px;color:var(--muted);margin-top:6px;"></div>
            </div>
            <div class="grid grid-3">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Monto</div>
                    <div id="concepto-detalle-monto" style="font-weight:800;margin-top:3px;color:var(--primary);"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Pagos</div>
                    <div id="concepto-detalle-pagos" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Estado</div>
                    <div id="concepto-detalle-estado" style="font-weight:800;margin-top:3px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-concepto" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(620px,94vw);max-height:92vh;overflow:auto;padding:0;">
        <div class="card-header" style="position:sticky;top:0;z-index:2;">
            <div>
                <span id="modal-concepto-title" class="card-title"><i class="fas fa-tag" style="color:var(--primary);"></i> Nuevo Concepto</span>
                <div id="modal-concepto-subtitle" style="font-size:12px;color:var(--muted);margin-top:2px;">Concepto, tipo y monto de cobro.</div>
            </div>
            <button type="button" onclick="cerrarConceptoModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <form id="form-concepto-modal" method="POST" action="{{ route('conceptos.store') }}">
            @csrf
            <input type="hidden" name="_method" id="concepto-method" value="POST">
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Nombre del Concepto *</label>
                    <input type="text" name="nombre" id="concepto-nombre" class="form-control" required placeholder="Ej: Mensualidad Primaria">
                </div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Tipo *</label>
                        <select name="tipo" id="concepto-tipo" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            <option value="mensualidad">Mensualidad</option>
                            <option value="matricula">Matrícula</option>
                            <option value="taller">Taller</option>
                            <option value="otros">Otros</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Monto (S/.) *</label>
                        <input type="number" name="monto" id="concepto-monto" class="form-control" step="0.01" min="0" required placeholder="0.00">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" id="concepto-descripcion" class="form-control" rows="3" placeholder="Descripción opcional del concepto..."></textarea>
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="activo" id="concepto-activo" value="1" style="width:18px;height:18px;">
                        <span class="form-label" style="margin-bottom:0;">Concepto activo</span>
                    </label>
                </div>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;padding:18px 22px;border-top:1px solid var(--border);background:#f7fafd;position:sticky;bottom:0;">
                <button type="button" onclick="cerrarConceptoModal()" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Concepto</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const conceptoStoreUrl = "{{ route('conceptos.store') }}";

function conceptoTexto(value) {
    return value || '—';
}

function conceptoMoneda(value) {
    return 'S/. ' + (parseFloat(value) || 0).toFixed(2);
}

function conceptoTipoLabel(value) {
    return value ? value.charAt(0).toUpperCase() + value.slice(1) : '—';
}

function abrirConceptoModal(data = null) {
    const editing = !!data?.action;
    const modal = document.getElementById('modal-concepto');
    const form = document.getElementById('form-concepto-modal');
    if (!modal || !form) return;

    form.reset();
    form.action = editing ? data.action : conceptoStoreUrl;
    document.getElementById('concepto-method').value = editing ? 'PUT' : 'POST';
    document.getElementById('modal-concepto-title').innerHTML = editing
        ? '<i class="fas fa-edit" style="color:var(--primary);"></i> Editar Concepto'
        : '<i class="fas fa-tag" style="color:var(--primary);"></i> Nuevo Concepto';
    document.getElementById('modal-concepto-subtitle').textContent = editing
        ? 'Actualiza concepto, tipo, monto y estado.'
        : 'Concepto, tipo y monto de cobro.';
    document.getElementById('concepto-nombre').value = data?.nombre || '';
    document.getElementById('concepto-tipo').value = data?.tipo || '';
    document.getElementById('concepto-monto').value = data?.monto !== undefined && data?.monto !== null ? Number(data.monto).toFixed(2) : '';
    document.getElementById('concepto-descripcion').value = data?.descripcion || '';
    document.getElementById('concepto-activo').checked = editing ? !!data?.activo : true;

    modal.style.display = 'flex';
    setTimeout(() => document.getElementById('concepto-nombre')?.focus(), 60);
}

function cerrarConceptoModal() {
    document.getElementById('modal-concepto').style.display = 'none';
}

function abrirConceptoDetalleModal(data) {
    document.getElementById('concepto-detalle-tipo').textContent = conceptoTipoLabel(data?.tipo);
    document.getElementById('concepto-detalle-nombre').textContent = conceptoTexto(data?.nombre);
    document.getElementById('concepto-detalle-descripcion').textContent = conceptoTexto(data?.descripcion);
    document.getElementById('concepto-detalle-monto').textContent = conceptoMoneda(data?.monto);
    document.getElementById('concepto-detalle-pagos').textContent = data?.pagos_count ?? 0;
    document.getElementById('concepto-detalle-estado').textContent = data?.activo ? 'Activo' : 'Inactivo';
    document.getElementById('modal-concepto-detalle').style.display = 'flex';
}

function cerrarConceptoDetalleModal() {
    document.getElementById('modal-concepto-detalle').style.display = 'none';
}

function conceptoDataDesdeBoton(button) {
    try {
        return JSON.parse(button.getAttribute('data-concepto') || '{}');
    } catch (error) {
        console.error('No se pudo leer la información del concepto.', error);
        return null;
    }
}

document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-concepto-action]');
    if (!button) return;

    const data = conceptoDataDesdeBoton(button);
    if (!data) return;

    if (button.dataset.conceptoAction === 'ver') {
        abrirConceptoDetalleModal(data);
    }

    if (button.dataset.conceptoAction === 'editar') {
        abrirConceptoModal(data);
    }
});
</script>
@endpush
