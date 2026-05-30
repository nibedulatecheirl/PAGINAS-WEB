@extends('layouts.app')
@section('title', 'Matrículas')
@section('page-title', 'Gestión de Matrículas')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <p style="color:var(--muted);font-size:13px;">{{ $matriculas->total() }} matrículas encontradas</p>
    <button type="button" onclick="abrirMatriculaModal()" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva Matrícula</button>
</div>

{{-- Filtros --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 22px;">
        <form method="GET" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:200px;">
                <input type="text" name="buscar" class="form-control"
                    placeholder="Nombre, apellido o DNI del alumno..." value="{{ request('buscar') }}">
            </div>
            <select name="grado_id" class="form-control" style="min-width:160px;">
                <option value="">Todos los grados</option>
                @foreach($grados as $g)
                    <option value="{{ $g->id }}" {{ request('grado_id')==$g->id ? 'selected':'' }}>{{ $g->nombre }}</option>
                @endforeach
            </select>
            <select name="anio" class="form-control" style="min-width:120px;">
                <option value="">Todos los años</option>
                @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ request('anio')==$y ? 'selected':'' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
            <a href="{{ route('matriculas.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i></a>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>N° Matrícula</th>
                    <th>Alumno</th>
                    <th>Grado</th>
                    <th>Sección</th>
                    <th>Año</th>
                    <th>Fecha Matrícula</th>
                    <th>Estado</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($matriculas as $m)
                @php
                    $matriculaData = [
                        "action" => route("matriculas.update", $m),
                        "numero" => $m->numero,
                        "alumno_id" => $m->alumno_id,
                        "alumno_nombre" => $m->alumno?->nombre_completo,
                        "alumno_dni" => $m->alumno?->dni,
                        "grado_id" => $m->grado_id,
                        "grado_nombre" => $m->grado?->nombre,
                        "seccion_id" => $m->seccion_id,
                        "seccion_nombre" => $m->seccion?->nombre,
                        "anio_escolar" => $m->anio_escolar,
                        "fecha_matricula" => $m->fecha_matricula?->format("Y-m-d"),
                        "fecha_matricula_label" => $m->fecha_matricula?->format("d/m/Y"),
                        "estado" => $m->estado,
                        "observaciones" => $m->observaciones,
                    ];
                @endphp
                <tr>
                    <td><span style="font-family:monospace;font-size:12px;">{{ $m->numero }}</span></td>
                    <td>
                        <div style="font-weight:600;">{{ $m->alumno->nombre_completo ?? '—' }}</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $m->alumno->dni ?? '' }}</div>
                    </td>
                    <td>{{ $m->grado->nombre ?? '—' }}</td>
                    <td style="font-weight:700;color:var(--primary-l);">Sec. {{ $m->seccion->nombre ?? '—' }}</td>
                    <td>{{ $m->anio_escolar }}</td>
                    <td style="font-size:13px;">{{ $m->fecha_matricula?->format('d/m/Y') }}</td>
                    <td>
                        @php $ec = $m->estado === 'activo' ? 'badge-success' : ($m->estado === 'retirado' ? 'badge-danger' : 'badge-warning'); @endphp
                        <span class="badge {{ $ec }}">{{ ucfirst($m->estado) }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <button type="button"
                                data-matricula-action="ver"
                                data-matricula='@json($matriculaData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                class="btn btn-sm btn-secondary btn-icon" title="Ver"><i class="fas fa-eye"></i></button>
                            <button type="button"
                                data-matricula-action="editar"
                                data-matricula='@json($matriculaData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                class="btn btn-sm btn-secondary btn-icon"><i class="fas fa-edit"></i></button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:48px;color:var(--muted);">
                        <i class="fas fa-file-signature" style="font-size:36px;margin-bottom:12px;display:block;opacity:.3;"></i>
                        No se encontraron matrículas.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($matriculas->hasPages())
    <div style="padding:16px 22px;border-top:1px solid var(--border);">
        {{ $matriculas->links() }}
    </div>
    @endif
</div>

<div id="modal-matricula-detalle" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(680px,96vw);max-height:92vh;overflow:auto;padding:0;">
        <div class="card-header">
            <div>
                <span class="card-title"><i class="fas fa-file-signature" style="color:var(--primary);"></i> Detalle de Matrícula</span>
                <div id="matricula-detalle-numero" style="font-size:12px;color:var(--muted);margin-top:2px;"></div>
            </div>
            <button type="button" onclick="cerrarMatriculaDetalleModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <div style="padding:14px;border:1px solid var(--border);border-radius:12px;background:#f7fafd;margin-bottom:14px;">
                <div style="font-size:12px;color:var(--muted);font-weight:700;">Alumno</div>
                <div id="matricula-detalle-alumno" style="font-size:17px;font-weight:800;margin-top:3px;"></div>
                <div id="matricula-detalle-dni" style="font-size:12px;color:var(--muted);margin-top:2px;"></div>
            </div>
            <div class="grid grid-3">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Grado</div>
                    <div id="matricula-detalle-grado" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Sección</div>
                    <div id="matricula-detalle-seccion" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Año</div>
                    <div id="matricula-detalle-anio" style="font-weight:800;margin-top:3px;"></div>
                </div>
            </div>
            <div class="grid grid-2" style="margin-top:14px;">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Fecha</div>
                    <div id="matricula-detalle-fecha" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Estado</div>
                    <div id="matricula-detalle-estado" style="font-weight:800;margin-top:3px;"></div>
                </div>
            </div>
            <div style="padding:14px;border:1px solid var(--border);border-radius:12px;margin-top:14px;">
                <div style="font-size:12px;color:var(--muted);font-weight:700;margin-bottom:6px;">Observaciones</div>
                <div id="matricula-detalle-observaciones" style="font-size:13px;"></div>
            </div>
        </div>
    </div>
</div>

<div id="modal-matricula" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(760px,96vw);max-height:92vh;overflow:auto;padding:0;">
        <div class="card-header" style="position:sticky;top:0;z-index:2;">
            <div>
                <span id="modal-matricula-title" class="card-title"><i class="fas fa-file-signature" style="color:var(--primary);"></i> Nueva Matrícula</span>
                <div id="modal-matricula-subtitle" style="font-size:12px;color:var(--muted);margin-top:2px;">Asigna alumno, grado, sección y año escolar.</div>
            </div>
            <button type="button" onclick="cerrarMatriculaModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>

        <form id="form-matricula-modal" method="POST" action="{{ route('matriculas.store') }}">
            @csrf
            <input type="hidden" name="_method" id="matricula-method" value="POST">
            <div class="card-body">
                <div class="form-group" id="matricula-alumno-select-wrap">
                    <label class="form-label">Alumno *</label>
                    <select name="alumno_id" id="matricula-alumno" class="form-control" required>
                        <option value="">Seleccionar alumno...</option>
                        @foreach($alumnos as $a)
                            <option value="{{ $a->id }}">{{ $a->nombre_completo }} — DNI: {{ $a->dni }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="matricula-alumno-readonly" style="display:none;">
                    <label class="form-label">Alumno</label>
                    <div id="matricula-alumno-label" style="padding:12px 14px;background:#f7fafd;border:1.5px solid var(--border);border-radius:10px;font-weight:700;color:var(--text);"></div>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Grado *</label>
                        <select name="grado_id" id="matricula-grado" class="form-control" required>
                            <option value="">Seleccionar grado...</option>
                            @foreach($grados as $g)
                                <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sección *</label>
                        <select name="seccion_id" id="matricula-seccion" class="form-control" required>
                            <option value="">Primero selecciona un grado</option>
                            @foreach($secciones as $s)
                                <option value="{{ $s->id }}" data-grado="{{ $s->grado_id }}">
                                    Sección {{ $s->nombre }} ({{ ucfirst($s->turno) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-3">
                    <div class="form-group">
                        <label class="form-label">Año Escolar *</label>
                        <select name="anio_escolar" id="matricula-anio_escolar" class="form-control" required>
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha de Matrícula *</label>
                        <input type="date" name="fecha_matricula" id="matricula-fecha_matricula" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group" id="matricula-estado-wrap" style="display:none;">
                        <label class="form-label">Estado *</label>
                        <select name="estado" id="matricula-estado" class="form-control" disabled>
                            @foreach(['activo','retirado','trasladado'] as $estado)
                                <option value="{{ $estado }}">{{ ucfirst($estado) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" id="matricula-observaciones" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;padding:18px 22px;border-top:1px solid var(--border);background:#f7fafd;position:sticky;bottom:0;">
                <button type="button" onclick="cerrarMatriculaModal()" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Matrícula</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const matriculaStoreUrl = "{{ route('matriculas.store') }}";
const matriculaCurrentYear = @json((int) date('Y'));
const matriculaToday = @json(date('Y-m-d'));
const matriculaGrado = document.getElementById('matricula-grado');
const matriculaSeccion = document.getElementById('matricula-seccion');
const matriculaSecciones = Array.from(matriculaSeccion?.options || []);

function filtrarSeccionesMatricula(selectedSeccion = '') {
    if (!matriculaGrado || !matriculaSeccion) return;
    const gradoId = matriculaGrado.value;
    matriculaSeccion.innerHTML = '';
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.text = gradoId ? 'Seleccionar sección...' : 'Primero selecciona un grado';
    matriculaSeccion.add(placeholder);

    matriculaSecciones
        .filter(option => option.value && option.dataset.grado == gradoId)
        .forEach(option => matriculaSeccion.add(option.cloneNode(true)));

    if (selectedSeccion) matriculaSeccion.value = String(selectedSeccion);
}

matriculaGrado?.addEventListener('change', () => filtrarSeccionesMatricula());

function abrirMatriculaModal(data = null) {
    const modal = document.getElementById('modal-matricula');
    const form = document.getElementById('form-matricula-modal');
    if (!modal || !form) return;

    form.reset();
    form.action = data?.action || matriculaStoreUrl;
    document.getElementById('matricula-method').value = data ? 'PUT' : 'POST';
    document.getElementById('modal-matricula-title').innerHTML = data
        ? '<i class="fas fa-edit" style="color:var(--primary);"></i> Editar Matrícula'
        : '<i class="fas fa-file-signature" style="color:var(--primary);"></i> Nueva Matrícula';
    document.getElementById('modal-matricula-subtitle').textContent = data?.numero
        ? 'N° ' + data.numero
        : 'Asigna alumno, grado, sección y año escolar.';

    const alumnoSelectWrap = document.getElementById('matricula-alumno-select-wrap');
    const alumnoReadonly = document.getElementById('matricula-alumno-readonly');
    const alumnoSelect = document.getElementById('matricula-alumno');
    const estadoWrap = document.getElementById('matricula-estado-wrap');
    const estado = document.getElementById('matricula-estado');

    alumnoSelectWrap.style.display = data ? 'none' : '';
    alumnoReadonly.style.display = data ? '' : 'none';
    alumnoSelect.disabled = !!data;
    alumnoSelect.required = !data;
    estadoWrap.style.display = data ? '' : 'none';
    estado.disabled = !data;

    if (data) {
        document.getElementById('matricula-alumno-label').textContent = (data.alumno_nombre || 'Alumno') + ' — DNI: ' + (data.alumno_dni || '—');
        document.getElementById('matricula-grado').value = data.grado_id || '';
        filtrarSeccionesMatricula(data.seccion_id || '');
        document.getElementById('matricula-anio_escolar').value = data.anio_escolar || matriculaCurrentYear;
        document.getElementById('matricula-fecha_matricula').value = data.fecha_matricula || matriculaToday;
        document.getElementById('matricula-estado').value = data.estado || 'activo';
        document.getElementById('matricula-observaciones').value = data.observaciones || '';
    } else {
        document.getElementById('matricula-anio_escolar').value = matriculaCurrentYear;
        document.getElementById('matricula-fecha_matricula').value = matriculaToday;
        filtrarSeccionesMatricula();
    }

    modal.style.display = 'flex';
    setTimeout(() => (data ? matriculaGrado : alumnoSelect)?.focus(), 60);
}

function cerrarMatriculaModal() {
    const modal = document.getElementById('modal-matricula');
    if (modal) modal.style.display = 'none';
}

function matriculaDataDesdeBoton(button) {
    try {
        return JSON.parse(button.getAttribute('data-matricula') || '{}');
    } catch (error) {
        console.error('No se pudo leer la información de la matrícula.', error);
        return null;
    }
}

document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-matricula-action]');
    if (!button) return;

    const data = matriculaDataDesdeBoton(button);
    if (!data) return;

    if (button.dataset.matriculaAction === 'ver') {
        abrirMatriculaDetalleModal(data);
    }

    if (button.dataset.matriculaAction === 'editar') {
        abrirMatriculaModal(data);
    }
});

function matriculaTexto(value) {
    return value || '—';
}

function abrirMatriculaDetalleModal(data) {
    document.getElementById('matricula-detalle-numero').textContent = data?.numero ? 'N° ' + data.numero : '';
    document.getElementById('matricula-detalle-alumno').textContent = matriculaTexto(data?.alumno_nombre);
    document.getElementById('matricula-detalle-dni').textContent = data?.alumno_dni ? 'DNI: ' + data.alumno_dni : '';
    document.getElementById('matricula-detalle-grado').textContent = matriculaTexto(data?.grado_nombre);
    document.getElementById('matricula-detalle-seccion').textContent = data?.seccion_nombre ? 'Sec. ' + data.seccion_nombre : '—';
    document.getElementById('matricula-detalle-anio').textContent = matriculaTexto(data?.anio_escolar);
    document.getElementById('matricula-detalle-fecha').textContent = matriculaTexto(data?.fecha_matricula_label);
    document.getElementById('matricula-detalle-estado').textContent = data?.estado ? data.estado.charAt(0).toUpperCase() + data.estado.slice(1) : '—';
    document.getElementById('matricula-detalle-observaciones').textContent = matriculaTexto(data?.observaciones);
    document.getElementById('modal-matricula-detalle').style.display = 'flex';
}

function cerrarMatriculaDetalleModal() {
    document.getElementById('modal-matricula-detalle').style.display = 'none';
}
</script>
@endpush
