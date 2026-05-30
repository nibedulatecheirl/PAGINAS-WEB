@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── ESTILOS EXCLUSIVOS DASHBOARD ── --}}
<style>
    .welcome-banner {
        margin-bottom: 28px;
    }
    
    .dashboard-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 32px;
    }
    
    .modern-stat-card {
        --stat-accent: var(--primary);
        background: linear-gradient(180deg, #ffffff 0%, #f8fbfe 100%);
        border-radius: var(--radius);
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: var(--shadow);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid var(--border);
        border-top: 4px solid var(--stat-accent);
    }
    .modern-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 24px 42px -32px rgba(24, 50, 77, .9);
    }
    
    .icon-box {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background: color-mix(in srgb, var(--stat-accent) 14%, white);
        border: 1px solid color-mix(in srgb, var(--stat-accent) 24%, white);
        color: var(--stat-accent);
        flex-shrink: 0;
    }
    
    .modern-stat-card.blue   { --stat-accent: var(--primary); }
    .modern-stat-card.green  { --stat-accent: var(--success); }
    .modern-stat-card.orange { --stat-accent: var(--warning); }
    .modern-stat-card.red    { --stat-accent: var(--danger); }
    
    .stat-details .val {
        font-size: 25px;
        font-weight: 850;
        color: var(--text);
        display: block;
        line-height: 1;
    }
    .stat-details .lab {
        font-size: 13px;
        color: var(--muted);
        font-weight: 600;
        margin-top: 5px;
        display: block;
    }

    @supports not (color: color-mix(in srgb, red 50%, white)) {
        .icon-box {
            background: #e5eef8;
            border-color: #c2d7ea;
            color: var(--primary);
        }
        .modern-stat-card.green .icon-box { background:#e2f6ee; border-color:#b7e8d4; color:var(--success); }
        .modern-stat-card.orange .icon-box { background:#fff2dc; border-color:#f4d19a; color:var(--warning); }
        .modern-stat-card.red .icon-box { background:#fde8ea; border-color:#f3b9bf; color:var(--danger); }
    }

    .chart-container {
        background: var(--card);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
    }
    .chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .chart-title { font-size: 16px; font-weight: 700; color: var(--text); }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
        min-width: 400px;
    }
    .custom-table th {
        text-align: left;
        padding: 12px 16px;
        font-size: 12px;
        text-transform: uppercase;
        color: #75869a;
        font-weight: 600;
    }
    .custom-table td {
        padding: 16px;
        background: #f7fafd;
        font-size: 14px;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }
    .custom-table tr td:first-child { border-left: 1px solid var(--border); }
    .custom-table tr td:last-child { border-right: 1px solid var(--border); }
    .custom-table tr td:first-child { border-radius: 10px 0 0 10px; }
    .custom-table tr td:last-child { border-radius: 0 10px 10px 0; }

    /* MEDIA QUERIES DASHBOARD */
    @media (max-width: 1200px) {
        .dashboard-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 768px) {
        .welcome-banner { padding: 24px; text-align: center; }
        .welcome-banner h1 { font-size: 24px !important; }
        .dashboard-stats-grid {
            grid-template-columns: 1fr;
        }
        .modern-stat-card { flex-direction: column; text-align: center; gap: 12px; }
        .chart-header { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="welcome-banner">
    <div style="position:relative; z-index: 2;">
        <h1 style="font-size: 28px; font-weight: 800; margin-bottom: 8px;">¡Bienvenido de nuevo, {{ auth()->user()->name }}!</h1>
        <p style="opacity: 0.9; font-size: 15px;">Aquí tienes el resumen administrativo de hoy para tu institución educativa.</p>
    </div>
</div>

<div class="dashboard-stats-grid">
    <div class="modern-stat-card blue">
        <div class="icon-box"><i class="fas fa-user-graduate"></i></div>
        <div class="stat-details">
            <span class="val">{{ $stats['total_alumnos'] }}</span>
            <span class="lab">Alumnos Inscritos</span>
        </div>
    </div>
    <div class="modern-stat-card green">
        <div class="icon-box"><i class="fas fa-check-circle"></i></div>
        <div class="stat-details">
            <span class="val">{{ $stats['total_matriculas'] }}</span>
            <span class="lab">Matrículas Activas</span>
        </div>
    </div>
    <div class="modern-stat-card orange">
        <div class="icon-box"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="stat-details">
            <span class="val">{{ $stats['total_personal'] }}</span>
            <span class="lab">Personal Activo</span>
        </div>
    </div>
    <div class="modern-stat-card red">
        <div class="icon-box"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-details">
            <span class="val">{{ $stats['pagos_pendientes'] }}</span>
            <span class="lab">Pagos Pendientes</span>
        </div>
    </div>
</div>

<div class="grid grid-2" style="margin-bottom:32px; gap: 32px;">
    <div class="chart-container">
        <div class="chart-header">
            <span class="chart-title">Flujo de Ingresos (S/.)</span>
            <span class="badge badge-success">Anual {{ date('Y') }}</span>
        </div>
        <div style="height: 300px; width: 100%;">
            <canvas id="chartIngresos"></canvas>
        </div>
    </div>

    <div class="chart-container">
        <div class="chart-header">
            <span class="chart-title">Distribución por Grados</span>
            <i class="fas fa-pie-chart" style="color: var(--muted)"></i>
        </div>
        <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
            <canvas id="chartGrados" style="max-width: 280px;"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-2" style="gap: 32px;">
    <div class="chart-container">
        <div class="chart-header">
            <span class="chart-title">Actividad Reciente de Pagos</span>
            <a href="{{ route('pagos.index') }}" style="font-size: 13px; color: var(--primary); font-weight: 600; text-decoration: none;">Ver todo</a>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Monto</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimosPagos as $pago)
                    <tr>
                        <td style="font-weight: 600;">{{ $pago->alumno->nombre_completo }}</td>
                        <td>S/. {{ number_format($pago->monto_pagado, 2) }}</td>
                        <td>
                            <span class="badge" style="background: {{ $pago->estado == 'pagado' ? '#e2f6ee' : '#fde8ea' }}; color: {{ $pago->estado == 'pagado' ? '#0f6b50' : '#982f39' }};">
                                {{ ucfirst($pago->estado) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="chart-container">
        <div class="chart-header">
            <span class="chart-title">Últimas Matriculaciones</span>
            <a href="{{ route('matriculas.index') }}" style="font-size: 13px; color: var(--primary); font-weight: 600; text-decoration: none;">Gestionar</a>
        </div>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Grado</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimasMatriculas as $m)
                    <tr>
                        <td style="font-weight: 600;">{{ $m->alumno->nombre_completo }}</td>
                        <td>{{ $m->grado->nombre }}</td>
                        <td><span class="badge badge-success">Activo</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
// ── Datos PHP → JS ──
const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
const ingresosPorMes = @json($pagosMensuales);
const gradosData     = @json($alumnosPorGrado);

// Construir array de 12 meses
const ingresosArr = meses.map((_, i) => {
    const mes = i + 1;
    return ingresosPorMes[mes] ? parseFloat(ingresosPorMes[mes].total) : 0;
});

// ── Chart 1: Línea de ingresos ──
const ctx1 = document.getElementById('chartIngresos').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: meses,
            datasets: [{
                label: 'Ingresos (S/.)',
                data: ingresosArr,
                borderColor: '#315f8f',
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(49, 95, 143, 0.22)');
                    gradient.addColorStop(1, 'rgba(49, 95, 143, 0)');
                    return gradient;
                },
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#315f8f',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.45,
            }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.05)' },
                ticks: { callback: v => 'S/.' + v.toLocaleString() }
            },
            x: { grid: { display: false } }
        }
    }
});

// ── Chart 2: Dona por grado ──
const ctx2 = document.getElementById('chartGrados').getContext('2d');
const gradoLabels = gradosData.map(g => g.grado ? g.grado.nombre : 'Sin grado');
const gradoTotals = gradosData.map(g => g.total);
const colores = [
    '#315f8f','#168a68','#c47a16','#c2414b','#6f669a',
    '#2b7fa8','#a84974','#6f8f2c','#b86b2d','#4f86bd','#297f73'
];

new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: gradoLabels.length ? gradoLabels : ['Sin datos'],
        datasets: [{
            data: gradoTotals.length ? gradoTotals : [1],
            backgroundColor: colores,
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: { size: 11 }, padding: 10, boxWidth: 12 }
            }
        }
    }
});
</script>
@endpush

