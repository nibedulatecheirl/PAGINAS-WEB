@extends('layouts.app')
@section('title', 'Personal')
@section('page-title', 'Gestión de Personal')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;gap:12px;flex-wrap:wrap;">
    <p style="color:var(--muted);font-size:13px;">{{ $personal->total() }} registros encontrados</p>
    <button type="button" onclick="abrirPersonalModal()" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Personal
    </button>
</div>

{{-- Filtros --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 22px;">
        <form method="GET" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:200px;">
                <input type="text" name="buscar" class="form-control"
                    placeholder="Nombre, apellido o DNI..." value="{{ request('buscar') }}">
            </div>
            <select name="tipo" class="form-control" style="min-width:160px;">
                <option value="">Todos los tipos</option>
                <option value="docente" {{ request('tipo')=='docente' ? 'selected':'' }}>Docente</option>
                <option value="administrativo" {{ request('tipo')=='administrativo' ? 'selected':'' }}>Administrativo</option>
                <option value="directivo" {{ request('tipo')=='directivo' ? 'selected':'' }}>Directivo</option>
                <option value="auxiliar" {{ request('tipo')=='auxiliar' ? 'selected':'' }}>Auxiliar</option>
            </select>
            <select name="estado" class="form-control" style="min-width:140px;">
                <option value="">Todos los estados</option>
                <option value="activo" {{ request('estado')=='activo' ? 'selected':'' }}>Activo</option>
                <option value="inactivo" {{ request('estado')=='inactivo' ? 'selected':'' }}>Inactivo</option>
                <option value="licencia" {{ request('estado')=='licencia' ? 'selected':'' }}>Licencia</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
            <a href="{{ route('personal.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i></a>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Personal</th>
                    <th>DNI</th>
                    <th>Tipo</th>
                    <th>Especialidad</th>
                    <th>Teléfono</th>
                    <th>Ingreso</th>
                    <th>Salario</th>
                    <th>Estado</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($personal as $p)
                @php
                    $personalData = [
                        "action" => route("personal.update", $p),
                        "dni" => $p->dni,
                        "nombres" => $p->nombres,
                        "apellidos" => $p->apellidos,
                        "nombre_completo" => $p->nombre_completo,
                        "tipo" => $p->tipo,
                        "especialidad" => $p->especialidad,
                        "telefono" => $p->telefono,
                        "email" => $p->email,
                        "direccion" => $p->direccion,
                        "fecha_ingreso" => $p->fecha_ingreso?->format("Y-m-d"),
                        "fecha_ingreso_label" => $p->fecha_ingreso?->format("d/m/Y"),
                        "salario" => (float) $p->salario,
                        "estado" => $p->estado,
                    ];
                @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#065f46,#168a68);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;flex-shrink:0;">
                                {{ strtoupper(substr($p->nombres,0,1)) }}{{ strtoupper(substr($p->apellidos,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;">{{ $p->nombre_completo }}</div>
                                <div style="font-size:11px;color:var(--muted);">{{ $p->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace;">{{ $p->dni }}</td>
                    <td><span class="badge badge-{{ $p->tipo_badge }}">{{ ucfirst($p->tipo) }}</span></td>
                    <td style="font-size:13px;color:var(--muted);">{{ $p->especialidad ?? '—' }}</td>
                    <td>{{ $p->telefono ?? '—' }}</td>
                    <td style="font-size:13px;">{{ $p->fecha_ingreso?->format('d/m/Y') }}</td>
                    <td style="font-weight:700;">S/. {{ number_format($p->salario, 2) }}</td>
                    <td>
                        @php $ec = $p->estado === 'activo' ? 'badge-success' : ($p->estado === 'licencia' ? 'badge-warning' : 'badge-secondary'); @endphp
                        <span class="badge {{ $ec }}">{{ ucfirst($p->estado) }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <button type="button"
                                data-personal-action="ver"
                                data-personal='@json($personalData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                class="btn btn-sm btn-secondary btn-icon" title="Ver"><i class="fas fa-eye"></i></button>
                            <button type="button"
                                data-personal-action="editar"
                                data-personal='@json($personalData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                class="btn btn-sm btn-secondary btn-icon" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('personal.destroy', $p) }}" onsubmit="return confirm('¿Desactivar?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Desactivar"><i class="fas fa-user-slash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:48px;color:var(--muted);">
                        <i class="fas fa-users" style="font-size:36px;margin-bottom:12px;display:block;opacity:.3;"></i>
                        No se encontró personal registrado.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($personal->hasPages())
        <div style="padding:16px 22px;border-top:1px solid var(--border);">
            {{ $personal->links() }}
        </div>
    @endif
</div>

<div id="modal-personal-detalle" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(760px,96vw);max-height:92vh;overflow:auto;padding:0;">
        <div class="card-header">
            <div>
                <span class="card-title"><i class="fas fa-id-badge" style="color:var(--primary);"></i> Detalle de Personal</span>
                <div id="personal-detalle-dni" style="font-size:12px;color:var(--muted);margin-top:2px;"></div>
            </div>
            <button type="button" onclick="cerrarPersonalDetalleModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;">
                <div id="personal-detalle-avatar" style="width:54px;height:54px;border-radius:14px;background:linear-gradient(135deg,#065f46,#168a68);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:18px;flex-shrink:0;"></div>
                <div>
                    <div id="personal-detalle-nombre" style="font-size:18px;font-weight:800;"></div>
                    <div id="personal-detalle-tipo" style="font-size:12px;color:var(--muted);margin-top:3px;"></div>
                </div>
            </div>
            <div class="grid grid-3">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Especialidad</div>
                    <div id="personal-detalle-especialidad" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Ingreso</div>
                    <div id="personal-detalle-ingreso" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Estado</div>
                    <div id="personal-detalle-estado" style="font-weight:800;margin-top:3px;"></div>
                </div>
            </div>
            <div class="grid grid-2" style="margin-top:14px;">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);font-weight:700;margin-bottom:8px;">Contacto</div>
                    <div id="personal-detalle-contacto" style="font-size:13px;line-height:1.7;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);font-weight:700;margin-bottom:8px;">Laboral</div>
                    <div id="personal-detalle-laboral" style="font-size:13px;line-height:1.7;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-personal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(880px,96vw);max-height:92vh;overflow:auto;padding:0;">
        <div class="card-header" style="position:sticky;top:0;z-index:2;">
            <div>
                <span id="modal-personal-title" class="card-title">
                    <i class="fas fa-id-badge" style="color:var(--primary);"></i> Nuevo Personal
                </span>
                <div id="modal-personal-subtitle" style="font-size:12px;color:var(--muted);margin-top:2px;">Datos personales y laborales.</div>
            </div>
            <button type="button" onclick="cerrarPersonalModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>

        <form id="form-personal-modal" method="POST" action="{{ route('personal.store') }}">
            @csrf
            <input type="hidden" name="_method" id="personal-method" value="POST">
            <div class="card-body">
                <section>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                        <div class="stat-icon blue" style="width:38px;height:38px;border-radius:10px;font-size:15px;"><i class="fas fa-id-card"></i></div>
                        <div>
                            <div style="font-weight:800;">Datos personales</div>
                            <div style="font-size:12px;color:var(--muted);">Identificación y contacto.</div>
                        </div>
                    </div>
                    <div class="grid grid-2">
                        <div class="form-group">
                            <label class="form-label">DNI *</label>
                            <input type="text" name="dni" id="personal-dni" class="form-control" maxlength="20" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipo *</label>
                            <select name="tipo" id="personal-tipo" class="form-control" required>
                                <option value="">Seleccionar...</option>
                                <option value="docente">Docente</option>
                                <option value="administrativo">Administrativo</option>
                                <option value="directivo">Directivo</option>
                                <option value="auxiliar">Auxiliar</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-2">
                        <div class="form-group">
                            <label class="form-label">Nombres *</label>
                            <input type="text" name="nombres" id="personal-nombres" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Apellidos *</label>
                            <input type="text" name="apellidos" id="personal-apellidos" class="form-control" required>
                        </div>
                    </div>
                    <div class="grid grid-3">
                        <div class="form-group">
                            <label class="form-label">Especialidad</label>
                            <input type="text" name="especialidad" id="personal-especialidad" class="form-control" placeholder="Ej: Matemáticas">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" id="personal-telefono" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="personal-email" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" id="personal-direccion" class="form-control">
                    </div>
                </section>

                <section style="border-top:1px solid var(--border);padding-top:20px;margin-top:4px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                        <div class="stat-icon green" style="width:38px;height:38px;border-radius:10px;font-size:15px;"><i class="fas fa-briefcase"></i></div>
                        <div>
                            <div style="font-weight:800;">Datos laborales</div>
                            <div style="font-size:12px;color:var(--muted);">Ingreso, salario y estado.</div>
                        </div>
                    </div>
                    <div class="grid grid-3">
                        <div class="form-group">
                            <label class="form-label">Fecha de Ingreso *</label>
                            <input type="date" name="fecha_ingreso" id="personal-fecha_ingreso" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Salario (S/.) *</label>
                            <input type="number" name="salario" id="personal-salario" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <select name="estado" id="personal-estado" class="form-control">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="licencia">Licencia</option>
                            </select>
                        </div>
                    </div>
                </section>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;padding:18px 22px;border-top:1px solid var(--border);background:#f7fafd;position:sticky;bottom:0;">
                <button type="button" onclick="cerrarPersonalModal()" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Personal</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
@php
    $personalEditarData = $personalParaEditar ? [
        "action" => route("personal.update", $personalParaEditar),
        "dni" => $personalParaEditar->dni,
        "nombres" => $personalParaEditar->nombres,
        "apellidos" => $personalParaEditar->apellidos,
        "tipo" => $personalParaEditar->tipo,
        "especialidad" => $personalParaEditar->especialidad,
        "telefono" => $personalParaEditar->telefono,
        "email" => $personalParaEditar->email,
        "direccion" => $personalParaEditar->direccion,
        "fecha_ingreso" => $personalParaEditar->fecha_ingreso?->format("Y-m-d"),
        "salario" => (float) $personalParaEditar->salario,
        "estado" => $personalParaEditar->estado,
    ] : null;
@endphp
<script>
const personalStoreUrl = "{{ route('personal.store') }}";
const personalToday = @json(date('Y-m-d'));
const personalEditarInicial = @json($personalEditarData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT);
const personalFields = [
    'dni','nombres','apellidos','tipo','especialidad','telefono','email','direccion',
    'fecha_ingreso','salario','estado'
];

function personalValue(value, fallback = '') {
    return value === null || value === undefined ? fallback : value;
}

function abrirPersonalModal(data = null) {
    const editing = !!data?.action;
    const modal = document.getElementById('modal-personal');
    const form = document.getElementById('form-personal-modal');
    if (!modal || !form) return;

    form.reset();
    form.action = editing ? data.action : personalStoreUrl;
    document.getElementById('personal-method').value = editing ? 'PUT' : 'POST';
    document.getElementById('modal-personal-title').innerHTML = editing
        ? '<i class="fas fa-edit" style="color:var(--primary);"></i> Editar Personal'
        : '<i class="fas fa-id-badge" style="color:var(--primary);"></i> Nuevo Personal';
    document.getElementById('modal-personal-subtitle').textContent = editing
        ? 'Actualización de datos personales y laborales.'
        : 'Datos personales y laborales.';

    personalFields.forEach(field => {
        const input = document.getElementById('personal-' + field);
        if (input) input.value = personalValue(data?.[field], '');
    });

    if (!editing) {
        document.getElementById('personal-fecha_ingreso').value = personalToday;
        document.getElementById('personal-estado').value = 'activo';
    }

    modal.style.display = 'flex';
    setTimeout(() => document.getElementById('personal-dni')?.focus(), 60);
}

function cerrarPersonalModal() {
    document.getElementById('modal-personal').style.display = 'none';
}

function personalDataDesdeBoton(button) {
    try {
        return JSON.parse(button.getAttribute('data-personal') || '{}');
    } catch (error) {
        console.error('No se pudo leer la información del personal.', error);
        return null;
    }
}

document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-personal-action]');
    if (!button) return;

    const data = personalDataDesdeBoton(button);
    if (!data) return;

    if (button.dataset.personalAction === 'ver') {
        abrirPersonalDetalleModal(data);
    }

    if (button.dataset.personalAction === 'editar') {
        abrirPersonalModal(data);
    }
});

function personalTexto(value) {
    return value || '—';
}
function personalHtml(value) {
    return String(personalTexto(value))
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
function abrirPersonalDetalleModal(data) {
    document.getElementById('personal-detalle-dni').textContent = data?.dni ? 'DNI: ' + data.dni : '';
    document.getElementById('personal-detalle-avatar').textContent = ((data?.nombres || '?').charAt(0) + (data?.apellidos || '?').charAt(0)).toUpperCase();
    document.getElementById('personal-detalle-nombre').textContent = data?.nombre_completo || ((data?.nombres || '') + ' ' + (data?.apellidos || '')).trim();
    document.getElementById('personal-detalle-tipo').textContent = data?.tipo ? data.tipo.charAt(0).toUpperCase() + data.tipo.slice(1) : '—';
    document.getElementById('personal-detalle-especialidad').textContent = personalTexto(data?.especialidad);
    document.getElementById('personal-detalle-ingreso').textContent = personalTexto(data?.fecha_ingreso_label);
    document.getElementById('personal-detalle-estado').textContent = data?.estado ? data.estado.charAt(0).toUpperCase() + data.estado.slice(1) : '—';
    document.getElementById('personal-detalle-contacto').innerHTML =
        '<strong>Teléfono:</strong> ' + personalHtml(data?.telefono) + '<br>' +
        '<strong>Email:</strong> ' + personalHtml(data?.email) + '<br>' +
        '<strong>Dirección:</strong> ' + personalHtml(data?.direccion);
    document.getElementById('personal-detalle-laboral').innerHTML =
        '<strong>Salario:</strong> S/. ' + (parseFloat(data?.salario) || 0).toFixed(2) + '<br>' +
        '<strong>Tipo:</strong> ' + personalHtml(data?.tipo) + '<br>' +
        '<strong>Estado:</strong> ' + personalHtml(data?.estado);
    document.getElementById('modal-personal-detalle').style.display = 'flex';
}
function cerrarPersonalDetalleModal() {
    document.getElementById('modal-personal-detalle').style.display = 'none';
}

if (personalEditarInicial) {
    abrirPersonalModal(personalEditarInicial);
}
</script>
@endpush
