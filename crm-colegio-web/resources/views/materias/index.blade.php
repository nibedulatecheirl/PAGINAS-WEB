@extends('layouts.app')
@section('title', 'Materias y Asignaciones')
@section('page-title', 'Materias y Asignación de Docentes')

@section('content')

<div class="grid grid-2" style="gap:24px;align-items:start;">

    {{-- Materias --}}
    <div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <h3 style="font-size:15px;font-weight:700;">Materias / Cursos ({{ $materias->count() }})</h3>
            <button type="button" onclick="abrirMateriaModal()" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nueva Materia
            </button>
        </div>

        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>Materia</th><th>Código</th><th>Nivel</th><th>H/sem</th><th>Asignaciones</th><th style="text-align:center;">Acc.</th></tr></thead>
                    <tbody>
                    @forelse($materias as $m)
                        @php
                            $materiaData = [
                                'id' => $m->id,
                                'nombre' => $m->nombre,
                                'codigo' => $m->codigo,
                                'nivel' => $m->nivel,
                                'horas' => $m->horas_semanales,
                                'color' => $m->color,
                                'activo' => $m->activo,
                                'asignaciones_count' => $m->asignaciones_count,
                            ];
                        @endphp
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:10px;height:10px;border-radius:50%;background:{{ $m->color }};flex-shrink:0;"></div>
                                    <span style="font-weight:600;">{{ $m->nombre }}</span>
                                </div>
                            </td>
                            <td style="font-family:monospace;font-size:12px;">{{ $m->codigo }}</td>
                            <td><span class="badge badge-secondary" style="font-size:11px;">{{ ucfirst($m->nivel) }}</span></td>
                            <td style="text-align:center;font-weight:700;">{{ $m->horas_semanales }}h</td>
                            <td style="text-align:center;">{{ $m->asignaciones_count }}</td>
                            <td>
                                <div style="display:flex;gap:5px;justify-content:center;">
                                    <button type="button"
                                        data-materia-action="ver"
                                        data-materia='@json($materiaData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                        class="btn btn-sm btn-secondary btn-icon" title="Ver"><i class="fas fa-eye"></i></button>
                                    <button type="button"
                                        data-materia-action="editar"
                                        data-materia='@json($materiaData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                        class="btn btn-sm btn-secondary btn-icon"><i class="fas fa-edit"></i></button>
                                    <form method="POST" action="{{ route('materias.destroy', $m) }}" onsubmit="return confirm('¿Desactivar?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger btn-icon {{ !$m->activo ? 'btn-secondary' : '' }}">
                                            <i class="fas fa-{{ $m->activo ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--muted);">Sin materias</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Asignaciones --}}
    <div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <h3 style="font-size:15px;font-weight:700;">Asignaciones {{ $anio }} ({{ $asignaciones->count() }})</h3>
            <button type="button" onclick="document.getElementById('modal-asig').style.display='flex'" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Asignar Docente
            </button>
        </div>

        <div class="card">
            <div style="max-height:480px;overflow-y:auto;">
                @forelse($asignaciones as $a)
                <div style="display:flex;align-items:center;gap:12px;padding:12px 18px;border-bottom:1px solid var(--border);">
                    <div style="width:10px;height:10px;border-radius:50%;background:{{ $a->materia->color ?? '#4f86bd' }};flex-shrink:0;"></div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;font-size:13px;">{{ $a->materia->nombre ?? '—' }}</div>
                        <div style="font-size:11px;color:var(--muted);">
                            {{ $a->seccion->grado->nombre ?? '' }} · Sec. {{ $a->seccion->nombre ?? '' }}
                        </div>
                        <div style="font-size:11px;color:var(--primary-l);margin-top:2px;">
                            <i class="fas fa-user-tie"></i> {{ $a->personal->nombre_completo ?? '—' }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('asignaciones.destroy', $a) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger btn-icon"><i class="fas fa-unlink"></i></button>
                    </form>
                </div>
                @empty
                <div style="padding:32px;text-align:center;color:var(--muted);">Sin asignaciones para {{ $anio }}</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- Modal Ver Materia --}}
<div id="modal-mat-detalle" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(560px,94vw);padding:0;">
        <div class="card-header">
            <div>
                <span class="card-title"><i class="fas fa-book-open" style="color:var(--primary);"></i> Detalle de Materia</span>
                <div style="font-size:12px;color:var(--muted);margin-top:2px;">Curso, nivel y asignaciones.</div>
            </div>
            <button type="button" onclick="cerrarMateriaDetalleModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <span id="mat-detalle-color" style="width:18px;height:18px;border-radius:6px;display:inline-block;background:#4f86bd;"></span>
                <div>
                    <div id="mat-detalle-nombre" style="font-size:18px;font-weight:800;"></div>
                    <div id="mat-detalle-codigo" style="font-size:12px;color:var(--muted);font-family:monospace;"></div>
                </div>
            </div>
            <div class="grid grid-3">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Nivel</div>
                    <div id="mat-detalle-nivel" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Horas semanales</div>
                    <div id="mat-detalle-horas" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Asignaciones</div>
                    <div id="mat-detalle-asignaciones" style="font-weight:800;margin-top:3px;"></div>
                </div>
            </div>
            <div style="margin-top:14px;">
                <span id="mat-detalle-estado" class="badge badge-success">Activo</span>
            </div>
        </div>
    </div>
</div>

{{-- Modal Materia --}}
<div id="modal-mat" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:28px;width:460px;box-shadow:0 20px 60px rgba(0,0,0,.3);">
        <div style="display:flex;justify-content:space-between;margin-bottom:20px;">
            <h3 id="modal-mat-title" style="font-size:16px;font-weight:700;">Nueva Materia</h3>
            <button type="button" onclick="cerrarMateriaModal()" style="background:none;border:none;font-size:18px;cursor:pointer;">✕</button>
        </div>
        <form id="form-mat" method="POST" action="{{ route('materias.store') }}">
            @csrf
            <input type="hidden" name="_method" id="mat-method" value="POST">
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" id="mat-nombre" class="form-control" required placeholder="Matemáticas">
                </div>
                <div class="form-group">
                    <label class="form-label">Código *</label>
                    <input type="text" name="codigo" id="mat-codigo" class="form-control" required placeholder="MAT01">
                </div>
            </div>
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Nivel</label>
                    <select name="nivel" id="mat-nivel" class="form-control">
                        <option value="todos">Todos</option>
                        <option value="inicial">Inicial</option>
                        <option value="primaria">Primaria</option>
                        <option value="secundaria">Secundaria</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Horas semanales</label>
                    <input type="number" name="horas_semanales" id="mat-horas" class="form-control" min="1" value="4">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Color identificador</label>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach(['#4f86bd','#168a68','#c47a16','#c2414b','#6f669a','#2b7fa8','#a84974','#b86b2d','#6f8f2c','#66758a'] as $c)
                    <label style="cursor:pointer;">
                        <input type="radio" name="color" value="{{ $c }}" style="display:none;" {{ $c==='#4f86bd'?'checked':'' }}>
                        <div style="width:28px;height:28px;border-radius:8px;background:{{ $c }};border:3px solid transparent;" onclick="marcarColor(this,'{{ $c }}')"></div>
                    </label>
                    @endforeach
                </div>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px;">
                <button type="button" onclick="cerrarMateriaModal()" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Asignación --}}
<div id="modal-asig" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:28px;width:480px;box-shadow:0 20px 60px rgba(0,0,0,.3);">
        <div style="display:flex;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:16px;font-weight:700;">Asignar Docente a Materia/Sección</h3>
            <button type="button" onclick="document.getElementById('modal-asig').style.display='none'" style="background:none;border:none;font-size:18px;cursor:pointer;">✕</button>
        </div>
        <form method="POST" action="{{ route('asignaciones.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Docente *</label>
                <select name="personal_id" class="form-control" required>
                    <option value="">Seleccionar docente...</option>
                    @foreach($docentes as $d)
                    <option value="{{ $d->id }}">{{ $d->nombre_completo }} — {{ $d->especialidad ?? 'Sin especialidad' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Materia *</label>
                <select name="materia_id" class="form-control" required>
                    <option value="">Seleccionar materia...</option>
                    @foreach($materias->where('activo',true) as $m)
                    <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Sección *</label>
                <select name="seccion_id" class="form-control" required>
                    <option value="">Seleccionar sección...</option>
                    @foreach($secciones as $s)
                    <option value="{{ $s->id }}">{{ $s->grado->nombre }} — Sec. {{ $s->nombre }} ({{ ucfirst($s->turno) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Año Escolar</label>
                <select name="anio_escolar" class="form-control">
                    @for($y=date('Y');$y>=2020;$y--)
                    <option value="{{ $y }}" {{ $anio==$y?'selected':'' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px;">
                <button type="button" onclick="document.getElementById('modal-asig').style.display='none'" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Asignar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const matRouteBase = "{{ url('materias') }}";
const matStoreUrl = "{{ route('materias.store') }}";
const matDefaultColor = '#4f86bd';

function abrirMateriaModal(data = null) {
    const form = document.getElementById('form-mat');
    form.reset();
    document.getElementById('modal-mat-title').textContent = data ? 'Editar Materia' : 'Nueva Materia';
    form.action = data ? matRouteBase + '/' + data.id : matStoreUrl;
    document.getElementById('mat-method').value = data ? 'PUT' : 'POST';
    document.getElementById('mat-nombre').value = data?.nombre || '';
    document.getElementById('mat-codigo').value = data?.codigo || '';
    document.getElementById('mat-nivel').value = data?.nivel || 'todos';
    document.getElementById('mat-horas').value = data?.horas || 4;
    seleccionarColorMateria(data?.color || matDefaultColor);
    document.getElementById('modal-mat').style.display = 'flex';
    setTimeout(() => document.getElementById('mat-nombre')?.focus(), 60);
}

function cerrarMateriaModal() {
    document.getElementById('modal-mat').style.display = 'none';
}

function materiaDataDesdeBoton(button) {
    try {
        return JSON.parse(button.getAttribute('data-materia') || '{}');
    } catch (error) {
        console.error('No se pudo leer la información de la materia.', error);
        return null;
    }
}

document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-materia-action]');
    if (!button) return;

    const data = materiaDataDesdeBoton(button);
    if (!data) return;

    if (button.dataset.materiaAction === 'ver') {
        abrirMateriaDetalleModal(data);
    }

    if (button.dataset.materiaAction === 'editar') {
        abrirMateriaModal(data);
    }
});

function editarMateria(id,nombre,codigo,nivel,horas,color) {
    abrirMateriaModal({ id, nombre, codigo, nivel, horas, color });
}
function abrirMateriaDetalleModal(data) {
    document.getElementById('mat-detalle-color').style.background = data?.color || '#4f86bd';
    document.getElementById('mat-detalle-nombre').textContent = data?.nombre || '—';
    document.getElementById('mat-detalle-codigo').textContent = data?.codigo ? 'Código: ' + data.codigo : 'Sin código';
    document.getElementById('mat-detalle-nivel').textContent = data?.nivel ? data.nivel.charAt(0).toUpperCase() + data.nivel.slice(1) : '—';
    document.getElementById('mat-detalle-horas').textContent = (data?.horas ?? 0) + 'h';
    document.getElementById('mat-detalle-asignaciones').textContent = data?.asignaciones_count ?? 0;
    const estado = document.getElementById('mat-detalle-estado');
    estado.className = data?.activo ? 'badge badge-success' : 'badge badge-secondary';
    estado.textContent = data?.activo ? 'Activo' : 'Inactivo';
    document.getElementById('modal-mat-detalle').style.display = 'flex';
}
function cerrarMateriaDetalleModal() {
    document.getElementById('modal-mat-detalle').style.display = 'none';
}
function seleccionarColorMateria(color) {
    document.querySelectorAll('[name="color"]').forEach(r => {
        const swatch = r.closest('label').querySelector('div');
        r.checked = r.value === color;
        swatch.style.border = r.checked ? '3px solid #18324d' : '3px solid transparent';
    });
}
function marcarColor(el, color) {
    seleccionarColorMateria(color);
}
seleccionarColorMateria(matDefaultColor);
</script>
@endpush

