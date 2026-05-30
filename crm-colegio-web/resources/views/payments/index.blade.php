@extends('layouts.app')
@section('title', 'Pagos')
@section('page-title', 'Gestión de Pagos')

@php
    $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
@endphp

@section('content')

{{-- Resumen financiero --}}
<div class="grid grid-3" style="margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="stat-value">S/. {{ number_format($resumen['total_pagado'], 2) }}</div>
            <div class="stat-label">Cobrado este mes</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <div class="stat-value">S/. {{ number_format($resumen['total_pendiente'], 2) }}</div>
            <div class="stat-label">Pendiente de cobro</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-info">
            <div class="stat-value">S/. {{ number_format($resumen['total_vencido'], 2) }}</div>
            <div class="stat-label">Deuda vencida</div>
        </div>
    </div>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;gap:12px;flex-wrap:wrap;">
    <p style="color:var(--muted);font-size:13px;">{{ $pagos->total() }} registros encontrados</p>
    <button type="button" onclick="abrirPagoModal()" class="btn btn-primary">
        <i class="fas fa-plus"></i> Registrar Pago
    </button>
</div>

{{-- Filtros --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 22px;">
        <form method="GET" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:200px;">
                <input type="text" name="buscar" class="form-control"
                    placeholder="Alumno, DNI o N° recibo..." value="{{ request('buscar') }}">
            </div>
            <select name="estado" class="form-control" style="min-width:140px;">
                <option value="">Todos los estados</option>
                <option value="pagado" {{ request('estado')=='pagado' ? 'selected':'' }}>Pagado</option>
                <option value="pendiente" {{ request('estado')=='pendiente' ? 'selected':'' }}>Pendiente</option>
                <option value="vencido" {{ request('estado')=='vencido' ? 'selected':'' }}>Vencido</option>
                <option value="anulado" {{ request('estado')=='anulado' ? 'selected':'' }}>Anulado</option>
            </select>
            <select name="mes" class="form-control" style="min-width:130px;">
                <option value="">Todos los meses</option>
                @foreach($meses as $i => $mes)
                    <option value="{{ $i + 1 }}" {{ request('mes')==($i + 1) ? 'selected':'' }}>{{ $mes }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
            <a href="{{ route('pagos.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i></a>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>N° Recibo</th>
                    <th>Alumno</th>
                    <th>Concepto</th>
                    <th>Mes / Año</th>
                    <th>Monto</th>
                    <th>Fecha Pago</th>
                    <th>Método</th>
                    <th>Estado</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($pagos as $pago)
                @php
                    $pagoData = [
                        "action" => route("pagos.update", $pago),
                        "numero_recibo" => $pago->numero_recibo,
                        "alumno_nombre" => $pago->alumno?->nombre_completo,
                        "alumno_dni" => $pago->alumno?->dni,
                        "concepto_nombre" => $pago->concepto?->nombre,
                        "anio_escolar" => $pago->anio_escolar,
                        "mes" => $pago->mes,
                        "mes_nombre" => $pago->nombre_mes,
                        "monto" => (float) $pago->monto,
                        "descuento" => (float) $pago->descuento,
                        "monto_pagado" => (float) $pago->monto_pagado,
                        "fecha_pago" => $pago->fecha_pago?->format("Y-m-d"),
                        "fecha_pago_label" => $pago->fecha_pago?->format("d/m/Y"),
                        "fecha_vencimiento" => $pago->fecha_vencimiento?->format("Y-m-d"),
                        "fecha_vencimiento_label" => $pago->fecha_vencimiento?->format("d/m/Y"),
                        "metodo_pago" => $pago->metodo_pago,
                        "estado" => $pago->estado,
                        "observaciones" => $pago->observaciones,
                    ];
                @endphp
                <tr>
                    <td><span style="font-family:monospace;font-size:12px;">{{ $pago->numero_recibo }}</span></td>
                    <td style="font-weight:600;">{{ $pago->alumno->nombre_completo ?? '—' }}</td>
                    <td>{{ $pago->concepto->nombre ?? '—' }}</td>
                    <td style="font-size:12px;color:var(--muted);">
                        {{ $pago->nombre_mes }} {{ $pago->anio_escolar }}
                    </td>
                    <td style="font-weight:700;color:var(--primary);">S/. {{ number_format($pago->monto_pagado, 2) }}</td>
                    <td style="font-size:13px;">{{ $pago->fecha_pago?->format('d/m/Y') }}</td>
                    <td><span style="font-size:12px;text-transform:capitalize;">{{ $pago->metodo_pago }}</span></td>
                    <td><span class="badge badge-{{ $pago->estado_badge }}">{{ ucfirst($pago->estado) }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <button type="button"
                                data-pago-action="ver"
                                data-pago='@json($pagoData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                class="btn btn-sm btn-secondary btn-icon" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button"
                                data-pago-action="editar"
                                data-pago='@json($pagoData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                class="btn btn-sm btn-secondary btn-icon" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:48px;color:var(--muted);">
                        <i class="fas fa-receipt" style="font-size:36px;margin-bottom:12px;display:block;opacity:.3;"></i>
                        No se encontraron pagos.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($pagos->hasPages())
        <div style="padding:16px 22px;border-top:1px solid var(--border);">
            {{ $pagos->links() }}
        </div>
    @endif
</div>

<div id="modal-pago-detalle" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(780px,96vw);max-height:92vh;overflow:auto;padding:0;">
        <div class="card-header">
            <div>
                <span class="card-title"><i class="fas fa-receipt" style="color:var(--success);"></i> Detalle del Pago</span>
                <div id="pago-detalle-recibo" style="font-size:12px;color:var(--muted);margin-top:2px;"></div>
            </div>
            <button type="button" onclick="cerrarPagoDetalleModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <div class="grid grid-2">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;background:#f7fafd;">
                    <div style="font-size:12px;color:var(--muted);font-weight:700;">Alumno</div>
                    <div id="pago-detalle-alumno" style="font-weight:800;margin-top:3px;"></div>
                    <div id="pago-detalle-dni" style="font-size:12px;color:var(--muted);margin-top:2px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;background:#f7fafd;">
                    <div style="font-size:12px;color:var(--muted);font-weight:700;">Concepto</div>
                    <div id="pago-detalle-concepto" style="font-weight:800;margin-top:3px;"></div>
                    <div id="pago-detalle-periodo" style="font-size:12px;color:var(--muted);margin-top:2px;"></div>
                </div>
            </div>
            <div class="grid grid-3" style="margin-top:14px;">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Monto</div>
                    <div id="pago-detalle-monto" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Descuento</div>
                    <div id="pago-detalle-descuento" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Pagado</div>
                    <div id="pago-detalle-pagado" style="font-weight:800;margin-top:3px;color:var(--success);"></div>
                </div>
            </div>
            <div class="grid grid-3" style="margin-top:14px;">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Fecha de pago</div>
                    <div id="pago-detalle-fecha" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Método</div>
                    <div id="pago-detalle-metodo" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Estado</div>
                    <div id="pago-detalle-estado" style="font-weight:800;margin-top:3px;"></div>
                </div>
            </div>
            <div style="padding:14px;border:1px solid var(--border);border-radius:12px;margin-top:14px;">
                <div style="font-size:12px;color:var(--muted);font-weight:700;margin-bottom:6px;">Observaciones</div>
                <div id="pago-detalle-observaciones" style="font-size:13px;"></div>
            </div>
        </div>
    </div>
</div>

<div id="modal-pago" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(880px,96vw);max-height:92vh;overflow:auto;padding:0;">
        <div class="card-header" style="position:sticky;top:0;z-index:2;">
            <div>
                <span id="modal-pago-title" class="card-title">
                    <i class="fas fa-money-bill-wave" style="color:var(--success);"></i> Registrar Pago
                </span>
                <div id="modal-pago-subtitle" style="font-size:12px;color:var(--muted);margin-top:2px;">Registro rápido de cobranza.</div>
            </div>
            <button type="button" onclick="cerrarPagoModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>

        <form id="form-pago-modal" method="POST" action="{{ route('pagos.store') }}">
            @csrf
            <input type="hidden" name="_method" id="pago-method" value="POST">
            <div class="card-body">
                <div id="pago-create-fields">
                    <div class="grid grid-2">
                        <div class="form-group">
                            <label class="form-label">Alumno *</label>
                            <select name="alumno_id" id="pago-alumno_id" class="form-control" required>
                                <option value="">Seleccionar alumno...</option>
                                @foreach($alumnos as $a)
                                    <option value="{{ $a->id }}">{{ $a->nombre_completo }} — {{ $a->dni }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Concepto de Pago *</label>
                            <select name="concepto_id" id="pago-concepto_id" class="form-control" required>
                                <option value="">Seleccionar concepto...</option>
                                @foreach($conceptos as $c)
                                    <option value="{{ $c->id }}" data-monto="{{ $c->monto }}">
                                        {{ $c->nombre }} — S/. {{ number_format($c->monto, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="pago-readonly-fields" style="display:none;margin-bottom:18px;padding:16px;border:1px solid var(--border);border-radius:12px;background:#f7fafd;">
                    <div class="grid grid-2">
                        <div>
                            <div style="font-size:11px;color:var(--muted);font-weight:700;text-transform:uppercase;">Alumno</div>
                            <div id="pago-alumno-label" style="font-weight:800;margin-top:3px;"></div>
                        </div>
                        <div>
                            <div style="font-size:11px;color:var(--muted);font-weight:700;text-transform:uppercase;">Concepto</div>
                            <div id="pago-concepto-label" style="font-weight:800;margin-top:3px;"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-3" id="pago-periodo-fields">
                    <div class="form-group">
                        <label class="form-label">Año Escolar *</label>
                        <select name="anio_escolar" id="pago-anio_escolar" class="form-control" required>
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mes (si aplica)</label>
                        <select name="mes" id="pago-mes" class="form-control">
                            <option value="">— No aplica —</option>
                            @foreach($meses as $i => $mes)
                                <option value="{{ $i + 1 }}">{{ $mes }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Método de Pago *</label>
                        <select name="metodo_pago" id="pago-metodo_pago" class="form-control" required>
                            @foreach(['efectivo','transferencia','tarjeta','cheque'] as $metodo)
                                <option value="{{ $metodo }}">{{ ucfirst($metodo) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Estado del Pago *</label>
                        <select name="estado" id="pago-estado" class="form-control" required>
                            @foreach(['pagado','pendiente','vencido','anulado'] as $estado)
                                <option value="{{ $estado }}">{{ ucfirst($estado) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Saldo calculado</label>
                        <input type="text" id="pago-saldo" class="form-control" value="S/. 0.00" readonly>
                    </div>
                </div>

                <div class="grid grid-3">
                    <div class="form-group">
                        <label class="form-label">Monto Total (S/.) *</label>
                        <input type="number" name="monto" id="pago-monto" class="form-control" step="0.01" min="0" required placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Descuento (S/.)</label>
                        <input type="number" name="descuento" id="pago-descuento" class="form-control" step="0.01" min="0" value="0.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Monto Pagado (S/.) *</label>
                        <input type="number" name="monto_pagado" id="pago-monto_pagado" class="form-control" step="0.01" min="0" required style="font-weight:800;color:var(--success);">
                    </div>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Fecha de Pago *</label>
                        <input type="date" name="fecha_pago" id="pago-fecha_pago" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha de Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" id="pago-fecha_vencimiento" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" id="pago-observaciones" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;padding:18px 22px;border-top:1px solid var(--border);background:#f7fafd;position:sticky;bottom:0;">
                <button type="button" onclick="cerrarPagoModal()" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Pago</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
@php
    $pagoEditarData = $pagoParaEditar ? [
        "action" => route("pagos.update", $pagoParaEditar),
        "numero_recibo" => $pagoParaEditar->numero_recibo,
        "alumno_nombre" => $pagoParaEditar->alumno?->nombre_completo,
        "alumno_dni" => $pagoParaEditar->alumno?->dni,
        "concepto_nombre" => $pagoParaEditar->concepto?->nombre,
        "anio_escolar" => $pagoParaEditar->anio_escolar,
        "mes" => $pagoParaEditar->mes,
        "monto" => (float) $pagoParaEditar->monto,
        "descuento" => (float) $pagoParaEditar->descuento,
        "monto_pagado" => (float) $pagoParaEditar->monto_pagado,
        "fecha_pago" => $pagoParaEditar->fecha_pago?->format("Y-m-d"),
        "fecha_vencimiento" => $pagoParaEditar->fecha_vencimiento?->format("Y-m-d"),
        "metodo_pago" => $pagoParaEditar->metodo_pago,
        "estado" => $pagoParaEditar->estado,
        "observaciones" => $pagoParaEditar->observaciones,
    ] : null;
@endphp
<script>
const pagoStoreUrl = "{{ route('pagos.store') }}";
const pagoCurrentYear = @json((int) date('Y'));
const pagoToday = @json(date('Y-m-d'));
const pagoAlumnoInicial = @json(request('alumno_id'));
const pagoEditarInicial = @json($pagoEditarData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT);

function pagoValue(value, fallback = '') {
    return value === null || value === undefined ? fallback : value;
}

function formatoPago(value) {
    const number = parseFloat(value) || 0;
    return number.toFixed(2);
}

function actualizarSaldoPago() {
    const monto = parseFloat(document.getElementById('pago-monto').value) || 0;
    const descuento = parseFloat(document.getElementById('pago-descuento').value) || 0;
    const pagado = parseFloat(document.getElementById('pago-monto_pagado').value) || 0;
    const neto = Math.max(0, monto - descuento);
    const saldo = Math.max(0, neto - pagado);
    const pagadoInput = document.getElementById('pago-monto_pagado');

    document.getElementById('pago-saldo').value = 'S/. ' + saldo.toFixed(2);
    pagadoInput.setCustomValidity(pagado > neto ? 'El monto pagado no puede superar el monto neto.' : '');

    const estado = document.getElementById('pago-estado');
    if (saldo > 0 && estado.value === 'pagado') {
        estado.value = 'pendiente';
    }
}

function abrirPagoModal(data = null) {
    const editing = !!data?.action;
    const modal = document.getElementById('modal-pago');
    const form = document.getElementById('form-pago-modal');
    if (!modal || !form) return;

    form.reset();
    form.action = editing ? data.action : pagoStoreUrl;
    document.getElementById('pago-method').value = editing ? 'PUT' : 'POST';
    document.getElementById('modal-pago-title').innerHTML = editing
        ? '<i class="fas fa-edit" style="color:var(--primary);"></i> Editar Pago'
        : '<i class="fas fa-money-bill-wave" style="color:var(--success);"></i> Registrar Pago';
    document.getElementById('modal-pago-subtitle').textContent = editing
        ? 'Recibo ' + (data.numero_recibo || '')
        : 'Registro rápido de cobranza.';

    document.getElementById('pago-create-fields').style.display = editing ? 'none' : '';
    document.getElementById('pago-readonly-fields').style.display = editing ? '' : 'none';
    document.getElementById('pago-alumno_id').disabled = editing;
    document.getElementById('pago-concepto_id').disabled = editing;
    document.getElementById('pago-anio_escolar').disabled = editing;
    document.getElementById('pago-mes').disabled = editing;

    if (editing) {
        document.getElementById('pago-alumno-label').textContent = (data.alumno_nombre || 'Alumno') + ' — DNI: ' + (data.alumno_dni || '—');
        document.getElementById('pago-concepto-label').textContent = data.concepto_nombre || '—';
        document.getElementById('pago-anio_escolar').value = data.anio_escolar || pagoCurrentYear;
        document.getElementById('pago-mes').value = data.mes || '';
        document.getElementById('pago-monto').value = formatoPago(data.monto);
        document.getElementById('pago-descuento').value = formatoPago(data.descuento);
        document.getElementById('pago-monto_pagado').value = formatoPago(data.monto_pagado);
        document.getElementById('pago-fecha_pago').value = data.fecha_pago || pagoToday;
        document.getElementById('pago-fecha_vencimiento').value = data.fecha_vencimiento || '';
        document.getElementById('pago-metodo_pago').value = data.metodo_pago || 'efectivo';
        document.getElementById('pago-estado').value = data.estado || 'pagado';
        document.getElementById('pago-observaciones').value = data.observaciones || '';
    } else {
        document.getElementById('pago-alumno_id').value = pagoValue(data?.alumno_id, '');
        document.getElementById('pago-concepto_id').value = '';
        document.getElementById('pago-anio_escolar').value = pagoCurrentYear;
        document.getElementById('pago-mes').value = '';
        document.getElementById('pago-metodo_pago').value = 'efectivo';
        document.getElementById('pago-estado').value = 'pagado';
        document.getElementById('pago-descuento').value = '0.00';
        document.getElementById('pago-monto').value = '';
        document.getElementById('pago-monto_pagado').value = '';
        document.getElementById('pago-fecha_pago').value = pagoToday;
        document.getElementById('pago-fecha_vencimiento').value = '';
        document.getElementById('pago-observaciones').value = '';
    }

    document.getElementById('pago-monto_pagado').dataset.manual = editing ? '1' : '';
    actualizarSaldoPago();
    modal.style.display = 'flex';
    setTimeout(() => (editing ? document.getElementById('pago-monto') : document.getElementById('pago-alumno_id'))?.focus(), 60);
}

function cerrarPagoModal() {
    document.getElementById('modal-pago').style.display = 'none';
}

function pagoDataDesdeBoton(button) {
    try {
        return JSON.parse(button.getAttribute('data-pago') || '{}');
    } catch (error) {
        console.error('No se pudo leer la información del pago.', error);
        return null;
    }
}

document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-pago-action]');
    if (!button) return;

    const data = pagoDataDesdeBoton(button);
    if (!data) return;

    if (button.dataset.pagoAction === 'ver') {
        abrirPagoDetalleModal(data);
    }

    if (button.dataset.pagoAction === 'editar') {
        abrirPagoModal(data);
    }
});

function pagoTexto(value) {
    return value || '—';
}

function pagoMoneda(value) {
    return 'S/. ' + (parseFloat(value) || 0).toFixed(2);
}

function abrirPagoDetalleModal(data) {
    document.getElementById('pago-detalle-recibo').textContent = data?.numero_recibo ? 'Recibo ' + data.numero_recibo : '';
    document.getElementById('pago-detalle-alumno').textContent = pagoTexto(data?.alumno_nombre);
    document.getElementById('pago-detalle-dni').textContent = data?.alumno_dni ? 'DNI: ' + data.alumno_dni : '';
    document.getElementById('pago-detalle-concepto').textContent = pagoTexto(data?.concepto_nombre);
    document.getElementById('pago-detalle-periodo').textContent = (data?.mes_nombre || 'Sin mes') + ' ' + pagoTexto(data?.anio_escolar);
    document.getElementById('pago-detalle-monto').textContent = pagoMoneda(data?.monto);
    document.getElementById('pago-detalle-descuento').textContent = pagoMoneda(data?.descuento);
    document.getElementById('pago-detalle-pagado').textContent = pagoMoneda(data?.monto_pagado);
    document.getElementById('pago-detalle-fecha').textContent = pagoTexto(data?.fecha_pago_label);
    document.getElementById('pago-detalle-metodo').textContent = data?.metodo_pago ? data.metodo_pago.charAt(0).toUpperCase() + data.metodo_pago.slice(1) : '—';
    document.getElementById('pago-detalle-estado').textContent = data?.estado ? data.estado.charAt(0).toUpperCase() + data.estado.slice(1) : '—';
    document.getElementById('pago-detalle-observaciones').textContent = pagoTexto(data?.observaciones);
    document.getElementById('modal-pago-detalle').style.display = 'flex';
}

function cerrarPagoDetalleModal() {
    document.getElementById('modal-pago-detalle').style.display = 'none';
}

document.getElementById('pago-concepto_id')?.addEventListener('change', function () {
    const option = this.options[this.selectedIndex];
    const monto = option?.dataset?.monto || '';
    const pagado = document.getElementById('pago-monto_pagado');
    document.getElementById('pago-monto').value = monto;
    if (!pagado.dataset.manual) {
        pagado.value = monto ? formatoPago(monto) : '';
    }
    actualizarSaldoPago();
});

document.getElementById('pago-monto')?.addEventListener('input', function () {
    const pagado = document.getElementById('pago-monto_pagado');
    if (!pagado.dataset.manual) {
        const monto = parseFloat(document.getElementById('pago-monto').value) || 0;
        const descuento = parseFloat(document.getElementById('pago-descuento').value) || 0;
        pagado.value = Math.max(0, monto - descuento).toFixed(2);
    }
    actualizarSaldoPago();
});
document.getElementById('pago-descuento')?.addEventListener('input', function () {
    const pagado = document.getElementById('pago-monto_pagado');
    if (!pagado.dataset.manual) {
        const monto = parseFloat(document.getElementById('pago-monto').value) || 0;
        const descuento = parseFloat(document.getElementById('pago-descuento').value) || 0;
        pagado.value = Math.max(0, monto - descuento).toFixed(2);
    }
    actualizarSaldoPago();
});
document.getElementById('pago-monto_pagado')?.addEventListener('input', function () {
    this.dataset.manual = '1';
    actualizarSaldoPago();
});
document.getElementById('pago-estado')?.addEventListener('change', actualizarSaldoPago);

if (pagoEditarInicial) {
    abrirPagoModal(pagoEditarInicial);
} else if (pagoAlumnoInicial) {
    abrirPagoModal({ alumno_id: pagoAlumnoInicial });
}
</script>
@endpush
