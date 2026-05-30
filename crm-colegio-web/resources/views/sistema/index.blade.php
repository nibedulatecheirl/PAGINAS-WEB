@extends('layouts.app')
@section('title', 'Gestión del Sistema')
@section('page-title', 'Mantenimiento y Respaldo')

@section('content')

<div class="grid grid-3" style="gap:24px;align-items:start;">

    {{-- COLUMNA 1: BACKUP --}}
    <div class="card" style="border-top: 4px solid #168a68;">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-download" style="color:#168a68;margin-right:8px;"></i>
                Copia de Seguridad
            </span>
        </div>
        <div class="card-body" style="text-align:center;padding:32px 24px;">
            <div style="font-size:48px;color:#b7e8d4;margin-bottom:16px;">
                <i class="fas fa-database"></i>
            </div>
            <h3 style="margin-bottom:12px;">Generar Backup</h3>
            <p style="color:var(--muted);font-size:14px;margin-bottom:24px;line-height:1.5;">
                Descarga una copia completa de toda la información actual de tu base de datos, incluyendo alumnos, pagos, notas y configuraciones.
            </p>
            
            <form method="POST" action="{{ route('sistema.backup') }}">
                @csrf
                <button type="submit" class="btn btn-success" style="width:100%;padding:12px;font-size:15px;">
                    <i class="fas fa-cloud-download-alt" style="margin-right:8px;"></i> Descargar .SQL Ahora
                </button>
            </form>
        </div>
    </div>

    {{-- COLUMNA 2: RESTAURACIÓN --}}
    <div class="card" style="border-top: 4px solid #4f86bd;">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-upload" style="color:#4f86bd;margin-right:8px;"></i>
                Restaurar Sistema
            </span>
        </div>
        <div class="card-body" style="text-align:center;padding:32px 24px;">
            <div style="font-size:48px;color:#dbeafe;margin-bottom:16px;">
                <i class="fas fa-sync"></i>
            </div>
            <h3 style="margin-bottom:12px;">Subir Backup</h3>
            <p style="color:var(--muted);font-size:14px;margin-bottom:24px;line-height:1.5;">
                Restaura el sistema a un punto anterior subiendo un archivo <strong>.sql</strong> previamente descargado. Esto reemplazará los datos actuales.
            </p>
            
            <form method="POST" action="{{ route('sistema.restore') }}" enctype="multipart/form-data"
                data-sistema-confirm
                data-confirm-title="Restaurar sistema"
                data-confirm-message="Esta acción sobrescribirá la base de datos actual con el archivo seleccionado.">
                @csrf
                <div class="form-group" style="text-align:left;">
                    <input type="file" name="backup_file" class="form-control" accept=".sql" required style="margin-bottom:16px;">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;font-size:15px;">
                    <i class="fas fa-history" style="margin-right:8px;"></i> Iniciar Restauración
                </button>
            </form>
        </div>
    </div>

    {{-- COLUMNA 3: RESET (ZONA DE PELIGRO) --}}
    <div class="card" style="border-top: 4px solid #c2414b; background-color:#fff5f6;">
        <div class="card-header" style="border-bottom-color:#fecaca;">
            <span class="card-title" style="color:#b91c1c;">
                <i class="fas fa-exclamation-triangle" style="margin-right:8px;"></i>
                Zona de Peligro
            </span>
        </div>
        <div class="card-body" style="text-align:center;padding:32px 24px;">
            <div style="font-size:48px;color:#fecaca;margin-bottom:16px;">
                <i class="fas fa-skull-crossbones"></i>
            </div>
            <h3 style="margin-bottom:12px;color:#982f39;">Resetear Sistema</h3>
            <p style="color:#b91c1c;font-size:14px;margin-bottom:24px;line-height:1.5;">
                Eliminará <strong>todos los registros</strong> (alumnos, matrículas, pagos, notas) y dejará el sistema en blanco para iniciar un nuevo periodo o empresa.
            </p>
            
            <form method="POST" action="{{ route('sistema.reset') }}"
                data-sistema-confirm
                data-confirm-title="Resetear sistema"
                data-confirm-message="Se eliminarán todos los registros y se repoblará la base con datos demo.">
                @csrf
                <div class="form-group" style="text-align:left;">
                    <label class="form-label" style="color:#982f39;">Escribe "RESETEAR" para confirmar:</label>
                    <input type="text" name="confirm_text" class="form-control" required style="border-color:#fca5a5;background:#fff;" placeholder="RESETEAR" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-danger" style="width:100%;padding:12px;font-size:15px;">
                    <i class="fas fa-trash-alt" style="margin-right:8px;"></i> Borrar Todos los Datos
                </button>
            </form>
        </div>
    </div>

</div>

<div id="modal-sistema-confirm" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(520px,94vw);padding:0;">
        <div class="card-header">
            <div>
                <span id="sistema-confirm-title" class="card-title"><i class="fas fa-shield-alt" style="color:var(--danger);"></i> Confirmar acción</span>
                <div style="font-size:12px;color:var(--muted);margin-top:2px;">Mantenimiento del sistema</div>
            </div>
            <button type="button" onclick="cerrarSistemaConfirmModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <p id="sistema-confirm-message" style="font-size:14px;line-height:1.6;color:var(--text);margin:0;"></p>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;padding:18px 22px;border-top:1px solid var(--border);background:#f7fafd;">
            <button type="button" onclick="cerrarSistemaConfirmModal()" class="btn btn-secondary">Cancelar</button>
            <button type="button" id="sistema-confirm-submit" class="btn btn-danger"><i class="fas fa-check"></i> Confirmar</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let sistemaFormPendiente = null;

function abrirSistemaConfirmModal(form) {
    sistemaFormPendiente = form;
    document.getElementById('sistema-confirm-title').innerHTML =
        '<i class="fas fa-shield-alt" style="color:var(--danger);"></i> ' + (form.dataset.confirmTitle || 'Confirmar acción');
    document.getElementById('sistema-confirm-message').textContent = form.dataset.confirmMessage || 'Confirma para continuar.';
    document.getElementById('modal-sistema-confirm').style.display = 'flex';
}

function cerrarSistemaConfirmModal() {
    document.getElementById('modal-sistema-confirm').style.display = 'none';
    sistemaFormPendiente = null;
}

document.querySelectorAll('[data-sistema-confirm]').forEach(form => {
    form.addEventListener('submit', function (event) {
        if (form.dataset.confirmed === '1') return;
        if (!form.checkValidity()) return;

        event.preventDefault();
        abrirSistemaConfirmModal(form);
    });
});

document.getElementById('sistema-confirm-submit')?.addEventListener('click', function () {
    if (!sistemaFormPendiente) return;
    sistemaFormPendiente.dataset.confirmed = '1';
    sistemaFormPendiente.submit();
});
</script>
@endpush

