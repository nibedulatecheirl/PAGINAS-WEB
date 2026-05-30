@extends('layouts.app')
@section('title', 'Secciones — '.$grado->nombre)
@section('page-title', 'Secciones de '.$grado->nombre)

@section('content')

<div style="display:flex;gap:12px;margin-bottom:20px;align-items:center;">
    <a href="{{ route('grados.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Grados</a>
    <span class="badge badge-primary" style="font-size:13px;padding:8px 14px;">{{ ucfirst($grado->nivel) }}</span>
</div>

<div class="grid grid-2" style="gap:24px;align-items:start;">

    {{-- Lista de secciones --}}
    <div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <h3 style="font-size:15px;font-weight:700;">Secciones ({{ $grado->secciones->count() }})</h3>
            <button type="button" onclick="abrirSeccionModal()" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nueva Sección
            </button>
        </div>

        @forelse($grado->secciones as $sec)
        @php
            $secData = [
                'id' => $sec->id,
                'nombre' => $sec->nombre,
                'turno' => $sec->turno,
                'capacidad' => $sec->capacidad,
                'matriculas_count' => $sec->matriculas->count(),
            ];
        @endphp
        <div class="card" style="margin-bottom:12px;padding:0;overflow:hidden;">
            <div style="display:flex;align-items:center;gap:0;">
                <div style="width:6px;height:100%;background:linear-gradient(180deg,#18324d,#4f86bd);min-height:80px;flex-shrink:0;"></div>
                <div style="flex:1;padding:16px 18px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <span style="font-size:18px;font-weight:800;color:var(--primary);">Sección {{ $sec->nombre }}</span>
                            <span class="badge badge-secondary" style="margin-left:10px;">{{ ucfirst($sec->turno) }}</span>
                        </div>
                        <div style="display:flex;gap:6px;">
                            <button type="button"
                                data-seccion-action="ver"
                                data-seccion='@json($secData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                class="btn btn-sm btn-secondary btn-icon" title="Ver"><i class="fas fa-eye"></i></button>
                            <button type="button"
                                data-seccion-action="editar"
                                data-seccion='@json($secData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                class="btn btn-sm btn-secondary btn-icon"><i class="fas fa-edit"></i></button>
                            @if($sec->matriculas->count() == 0)
                            <form method="POST" action="{{ route('secciones.destroy', $sec) }}" onsubmit="return confirm('¿Eliminar sección?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-icon"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </div>
                    <div style="display:flex;gap:20px;margin-top:8px;">
                        <div style="font-size:12px;color:var(--muted);">
                            <i class="fas fa-users"></i>
                            <strong>{{ $sec->matriculas->count() }}</strong>/{{ $sec->capacidad }} alumnos
                        </div>
                        <div style="font-size:12px;">
                            @php $pct = $sec->capacidad > 0 ? min(100, ($sec->matriculas->count()/$sec->capacidad)*100) : 0; @endphp
                            <div style="width:100px;height:6px;background:#ccd8e6;border-radius:3px;display:inline-block;vertical-align:middle;">
                                <div style="width:{{ $pct }}%;height:100%;background:{{ $pct>90?'#c2414b':($pct>70?'#c47a16':'#168a68') }};border-radius:3px;"></div>
                            </div>
                            <span style="color:var(--muted);margin-left:4px;">{{ round($pct) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card" style="padding:32px;text-align:center;color:var(--muted);">
            <i class="fas fa-door-open" style="font-size:32px;opacity:.3;display:block;margin-bottom:12px;"></i>
            Sin secciones registradas para este grado.
        </div>
        @endforelse
    </div>

    {{-- Docentes disponibles --}}
    <div>
        <div style="margin-bottom:14px;">
            <h3 style="font-size:15px;font-weight:700;">Docentes Activos</h3>
        </div>
        <div class="card">
            <div style="padding:12px 0;max-height:400px;overflow-y:auto;">
                @foreach($docentes as $d)
                <div style="display:flex;align-items:center;gap:12px;padding:10px 18px;border-bottom:1px solid var(--border);">
                    <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#065f46,#168a68);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:12px;flex-shrink:0;">
                        {{ strtoupper(substr($d->nombres,0,1))  }}{{ strtoupper(substr($d->apellidos,0,1)) }}
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:600;">{{ $d->nombre_completo }}</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $d->especialidad ?? 'Sin especialidad' }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- Modal Ver Sección --}}
<div id="modal-sec-detalle" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(520px,94vw);padding:0;">
        <div class="card-header">
            <div>
                <span class="card-title"><i class="fas fa-door-open" style="color:var(--primary);"></i> Detalle de Sección</span>
                <div style="font-size:12px;color:var(--muted);margin-top:2px;">{{ $grado->nombre }}</div>
            </div>
            <button type="button" onclick="cerrarSeccionDetalleModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <div style="padding:14px;border:1px solid var(--border);border-radius:12px;background:#f7fafd;margin-bottom:14px;">
                <div style="font-size:12px;color:var(--muted);font-weight:700;">Sección</div>
                <div id="sec-detalle-nombre" style="font-size:18px;font-weight:800;margin-top:3px;"></div>
            </div>
            <div class="grid grid-3">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Turno</div>
                    <div id="sec-detalle-turno" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Capacidad</div>
                    <div id="sec-detalle-capacidad" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Matriculados</div>
                    <div id="sec-detalle-matriculas" style="font-weight:800;margin-top:3px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Sección --}}
<div id="modal-sec" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:28px;width:420px;box-shadow:0 20px 60px rgba(0,0,0,.3);">
        <div style="display:flex;justify-content:space-between;margin-bottom:20px;">
            <h3 id="modal-sec-title" style="font-size:16px;font-weight:700;">Nueva Sección</h3>
            <button type="button" onclick="cerrarSeccionModal()" style="background:none;border:none;font-size:18px;cursor:pointer;color:var(--muted);">✕</button>
        </div>
        <form id="form-sec" method="POST" action="{{ route('grados.secciones.store', $grado) }}">
            @csrf
            <input type="hidden" name="_method" id="sec-method" value="POST">
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Nombre (letra) *</label>
                    <input type="text" name="nombre" id="sec-nombre" class="form-control" maxlength="10" required placeholder="A, B, C...">
                </div>
                <div class="form-group">
                    <label class="form-label">Turno *</label>
                    <select name="turno" id="sec-turno" class="form-control" required>
                        <option value="mañana">Mañana</option>
                        <option value="tarde">Tarde</option>
                        <option value="noche">Noche</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Capacidad máxima</label>
                <input type="number" name="capacidad" id="sec-capacidad" class="form-control" min="1" value="30">
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" onclick="cerrarSeccionModal()" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const secRouteBase = "{{ url('secciones') }}";
const secStoreUrl = "{{ route('grados.secciones.store', $grado) }}";

function abrirSeccionModal(data = null) {
    const form = document.getElementById('form-sec');
    form.reset();
    document.getElementById('modal-sec-title').textContent = data ? 'Editar Sección' : 'Nueva Sección';
    form.action = data ? secRouteBase + '/' + data.id : secStoreUrl;
    document.getElementById('sec-method').value = data ? 'PUT' : 'POST';
    document.getElementById('sec-nombre').value = data?.nombre || '';
    document.getElementById('sec-turno').value = data?.turno || 'mañana';
    document.getElementById('sec-capacidad').value = data?.capacidad || 30;
    document.getElementById('modal-sec').style.display = 'flex';
    setTimeout(() => document.getElementById('sec-nombre')?.focus(), 60);
}

function cerrarSeccionModal() {
    document.getElementById('modal-sec').style.display = 'none';
}

function seccionDataDesdeBoton(button) {
    try {
        return JSON.parse(button.getAttribute('data-seccion') || '{}');
    } catch (error) {
        console.error('No se pudo leer la información de la sección.', error);
        return null;
    }
}

document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-seccion-action]');
    if (!button) return;

    const data = seccionDataDesdeBoton(button);
    if (!data) return;

    if (button.dataset.seccionAction === 'ver') {
        abrirSeccionDetalleModal(data);
    }

    if (button.dataset.seccionAction === 'editar') {
        abrirSeccionModal(data);
    }
});

function editarSeccion(id, nombre, turno, capacidad) {
    abrirSeccionModal({ id, nombre, turno, capacidad });
}

function abrirSeccionDetalleModal(data) {
    document.getElementById('sec-detalle-nombre').textContent = 'Sección ' + (data?.nombre || '—');
    document.getElementById('sec-detalle-turno').textContent = data?.turno ? data.turno.charAt(0).toUpperCase() + data.turno.slice(1) : '—';
    document.getElementById('sec-detalle-capacidad').textContent = data?.capacidad ?? 0;
    document.getElementById('sec-detalle-matriculas').textContent = data?.matriculas_count ?? 0;
    document.getElementById('modal-sec-detalle').style.display = 'flex';
}

function cerrarSeccionDetalleModal() {
    document.getElementById('modal-sec-detalle').style.display = 'none';
}
</script>
@endpush

