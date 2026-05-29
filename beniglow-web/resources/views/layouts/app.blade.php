<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BeniGlow') | {{ $empresaGlobal->nombre_comercial ?? 'BeniGlow' }}</title>

    @if($empresaGlobal && $empresaGlobal->logo_url)
        <link rel="icon" href="{{ $empresaGlobal->logo_url }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        * { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }
        body { overflow-x: hidden; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .sidebar-link.active { background: linear-gradient(90deg, rgba(183,119,91,.22), transparent); border-left: 3px solid #d9a086; color: #ffe2d5; }
        .gradient-primary { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
        .gradient-card-1 { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); }
        .gradient-card-2 { background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); }
        .gradient-card-3 { background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); }
        .gradient-card-4 { background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); }
        .gradient-danger { background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .chart-card { animation: fadeInUp 0.5s ease-out; }
        @media (max-width: 640px) {
            .hide-mobile { display: none !important; }
            table { font-size: 12px; }
            .text-3xl { font-size: 1.5rem; }
        }
        .overflow-x-auto { -webkit-overflow-scrolling: touch; }
        @media (max-width: 1023px) {
            .sidebar-mobile-open { transform: translateX(0); }
        }
        [x-cloak] { display: none !important; }
        .report-print-header { display: none; }
        .report-shell { display: flex; flex-direction: column; gap: 1.25rem; }
        .report-hero {
            background: linear-gradient(135deg, #fff7ed 0%, #ffffff 48%, #ecfdf5 100%);
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            box-shadow: 0 14px 30px -24px rgba(15, 23, 42, .45);
        }
        .report-card, .report-sheet {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 14px 30px -26px rgba(15, 23, 42, .45);
        }
        .report-table-wrapper { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .report-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .report-table thead th {
            background: #f8fafc;
            color: #475569;
            font-size: .72rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            font-weight: 700;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        .report-table th, .report-table td { padding: .72rem .9rem; vertical-align: middle; }
        .report-table tbody tr + tr td { border-top: 1px solid #f1f5f9; }
        .report-table tbody tr:hover { background: #f8fafc; }
        .report-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 1rem;
        }
        .report-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
        }
        .report-kpi {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 12px 26px -24px rgba(15, 23, 42, .45);
        }
        .report-kpi-accent { color: #9a5f46; }
        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            html,
            body { background: #fff !important; }
            .print-hidden,
            [data-print-hidden="true"],
            .fixed.inset-0,
            .fixed.inset-y-0,
            aside,
            header.brand-topbar,
            .no-print {
                display: none !important;
                opacity: 0 !important;
                visibility: hidden !important;
            }
            .report-shell,
            .report-shell * {
                filter: none !important;
                opacity: 1 !important;
            }
            .flex.min-h-screen,
            .flex.min-h-screen > .flex-1 {
                display: block !important;
                min-height: auto !important;
            }
            .lg\:ml-64 {
                margin-left: 0 !important;
            }
            main {
                padding: 0 !important;
            }
            .report-print-header {
                display: flex !important;
                justify-content: space-between;
                align-items: flex-start;
                gap: 16px;
                padding-bottom: 10px;
                margin-bottom: 12px;
                border-bottom: 2px solid #334155;
            }
            .report-print-title {
                font-size: 20px !important;
                font-weight: 800 !important;
                color: #0f172a !important;
                margin: 0 !important;
            }
            .report-print-meta {
                font-size: 11px !important;
                color: #475569 !important;
                line-height: 1.45 !important;
            }
            .report-shell {
                gap: 10px !important;
            }
            .report-kpi-grid {
                display: grid !important;
                grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
                gap: 6px !important;
            }
            .report-card-grid {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 8px !important;
            }
            .report-hero {
                display: none !important;
            }
            .report-card,
            .report-sheet,
            .report-kpi,
            .bg-white.rounded-2xl.shadow-md {
                border: 1px solid #cbd5e1 !important;
                box-shadow: none !important;
                border-radius: 8px !important;
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .report-kpi {
                padding: 8px 10px !important;
                min-height: auto !important;
            }
            .report-kpi p {
                margin: 0 !important;
            }
            .report-kpi p:nth-child(2) {
                font-size: 17px !important;
                line-height: 1.2 !important;
                margin-top: 4px !important;
            }
            .report-kpi p:nth-child(3) {
                font-size: 9.5px !important;
                margin-top: 3px !important;
            }
            .report-table-wrapper {
                overflow: visible !important;
            }
            .report-table {
                border-collapse: collapse !important;
                table-layout: auto !important;
                font-size: 10.5px !important;
            }
            .report-table th,
            .report-table td {
                padding: 6px 7px !important;
                border: 1px solid #dbe3ed !important;
                color: #111827 !important;
            }
            .report-table thead th {
                background: #e5e7eb !important;
                color: #111827 !important;
            }
            .report-table tbody tr:hover {
                background: transparent !important;
            }
            table {
                width: 100% !important;
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            h1, h2, h3, p {
                color: #111827 !important;
            }
            .text-white,
            .text-emerald-600,
            .text-blue-600,
            .text-red-600,
            .text-yellow-700,
            .text-orange-700,
            .text-green-700 {
                color: #111827 !important;
            }
            .rounded-full {
                border-radius: 999px !important;
            }
            a[href]::after {
                content: "";
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/beniglow-theme.css') }}">
    @yield('head')
</head>
<body class="bg-slate-100">
<div class="flex min-h-screen" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
    <aside class="print-hidden brand-sidebar bg-slate-900 text-white w-64 fixed inset-y-0 left-0 z-30 transition-transform duration-300 lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="p-5 border-b border-slate-800 flex items-center gap-3">
            @if($empresaGlobal && $empresaGlobal->logo_url)
                <img src="{{ $empresaGlobal->logo_url }}" class="brand-logo-shell w-12 h-12 rounded-xl object-contain p-1" alt="BeniGlow">
            @else
                <div class="brand-logo-shell w-12 h-12 rounded-xl gradient-primary flex items-center justify-center">
                    <i class="fas fa-spa text-white"></i>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <h1 class="font-bold text-base truncate">{{ $empresaGlobal->nombre_comercial ?? 'BeniGlow' }}</h1>
                <p class="text-xs text-[#d9a086]">Back-office e-commerce</p>
            </div>
        </div>

        <nav class="py-4 overflow-y-auto" style="max-height: calc(100vh - 80px);">
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt w-5"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('ventas.pos') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('ventas.pos') ? 'active' : '' }}">
                <i class="fas fa-cash-register w-5"></i><span>Venta mostrador</span>
            </a>

            <p class="px-5 mt-4 mb-2 text-xs uppercase text-slate-500 font-semibold">E-commerce</p>
            <a href="{{ route('ecommerce.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('ecommerce.*') ? 'active' : '' }}">
                <i class="fas fa-chart-simple w-5"></i><span>Panel e-commerce</span>
            </a>
            <a href="{{ route('storefront') }}" target="_blank" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition">
                <i class="fas fa-store w-5"></i><span>Tienda pública</span>
            </a>
            <a href="{{ route('pedidos-web.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('pedidos-web.*') ? 'active' : '' }}">
                <i class="fas fa-bag-shopping w-5"></i><span>Pedidos web</span>
            </a>
            <a href="{{ route('productos.index', ['visible_web' => 'si']) }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('productos.*') && request('visible_web') === 'si' ? 'active' : '' }}">
                <i class="fas fa-box-open w-5"></i><span>Productos web</span>
            </a>

            <p class="px-5 mt-4 mb-2 text-xs uppercase text-slate-500 font-semibold">Operaciones</p>
            <a href="{{ route('ventas.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('ventas.index') ? 'active' : '' }}">
                <i class="fas fa-receipt w-5"></i><span>Ventas</span>
            </a>
            <a href="{{ route('compras.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('compras.*') ? 'active' : '' }}">
                <i class="fas fa-truck w-5"></i><span>Compras</span>
            </a>
            <a href="{{ route('caja.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('caja.*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave w-5"></i><span>Caja mostrador</span>
            </a>

            <p class="px-5 mt-4 mb-2 text-xs uppercase text-slate-500 font-semibold">Inventario</p>
            <a href="{{ route('productos.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('productos.*') && !request()->has('visible_web') ? 'active' : '' }}">
                <i class="fas fa-box w-5"></i><span>Productos</span>
            </a>
            <a href="{{ route('categorias.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                <i class="fas fa-tags w-5"></i><span>Categorías</span>
            </a>
            <a href="{{ route('promociones.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('promociones.*') ? 'active' : '' }}">
                <i class="fas fa-percent w-5"></i><span>Promociones</span>
            </a>

            <p class="px-5 mt-4 mb-2 text-xs uppercase text-slate-500 font-semibold">Contactos</p>
            <a href="{{ route('clientes.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <i class="fas fa-users w-5"></i><span>Clientes</span>
            </a>
            <a href="{{ route('proveedores.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
                <i class="fas fa-truck-loading w-5"></i><span>Proveedores</span>
            </a>

            <p class="px-5 mt-4 mb-2 text-xs uppercase text-slate-500 font-semibold">Análisis</p>
            <a href="{{ route('reportes.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line w-5"></i><span>Reportes</span>
            </a>

            @if(auth()->user()->isAdmin())
                <p class="px-5 mt-4 mb-2 text-xs uppercase text-slate-500 font-semibold">Sistema</p>
                <a href="{{ route('usuarios.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield w-5"></i><span>Usuarios</span>
                </a>
                <a href="{{ route('configuracion.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
                    <i class="fas fa-cog w-5"></i><span>Configuración</span>
                </a>
                <a href="{{ route('backup.index') }}" class="sidebar-link flex items-center gap-3 px-5 py-3 text-slate-300 hover:bg-slate-800 transition {{ request()->routeIs('backup.*') ? 'active' : '' }}">
                    <i class="fas fa-database w-5"></i><span>Backup</span>
                </a>
            @endif

            <div class="px-5 mt-6">
                <form method="POST" action="{{ route('logout') }}" x-data="{ submitting: false }" @submit="if (submitting) { $event.preventDefault(); return false; } submitting = true">
                    @csrf
                    <button type="submit" :disabled="submitting" :class="{ 'opacity-70 cursor-wait': submitting }" class="w-full flex items-center gap-3 px-3 py-2.5 bg-red-600/20 hover:bg-red-600/40 rounded-lg text-red-300 transition">
                        <i class="fas fa-sign-out-alt"></i><span x-text="submitting ? 'Cerrando...' : 'Cerrar sesión'">Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="print-hidden fixed inset-0 bg-black/50 z-20 lg:hidden" data-print-hidden="true" style="display:none;"></div>

    <div class="flex-1 lg:ml-64 min-w-0">
        <header class="print-hidden brand-topbar bg-white shadow-sm border-b border-slate-200 sticky top-0 z-20">
            <div class="flex items-center justify-between px-3 sm:px-6 py-3">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-600 hover:text-slate-900 flex-shrink-0">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-base sm:text-lg font-semibold text-slate-800 truncate">@yield('header', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
                    <div class="hidden md:flex items-center gap-2 text-sm text-slate-600">
                        <i class="far fa-calendar"></i>
                        <span class="hidden lg:inline">{{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</span>
                        <span class="lg:hidden">{{ now()->format('d/m/Y') }}</span>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 hover:bg-slate-100 px-2 sm:px-3 py-2 rounded-lg transition">
                            <div class="w-8 h-8 gradient-primary rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500">{{ auth()->user()->role->nombre ?? 'Usuario' }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-slate-400 hidden sm:inline"></i>
                        </button>
                        <div x-show="open" @click.outside="open = false" class="print-hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 py-2" data-print-hidden="true" style="display:none;">
                            <p class="px-4 py-2 text-xs text-slate-500 border-b border-slate-100">Conectado como</p>
                            <p class="px-4 py-1 text-sm font-semibold">{{ auth()->user()->name }}</p>
                            <p class="px-4 pb-2 text-xs text-slate-500">{{ auth()->user()->email }}</p>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100" x-data="{ submitting: false }" @submit="if (submitting) { $event.preventDefault(); return false; } submitting = true">
                                @csrf
                                <button type="submit" :disabled="submitting" :class="{ 'opacity-70 cursor-wait': submitting }" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i><span x-text="submitting ? 'Cerrando...' : 'Cerrar sesión'">Cerrar sesión</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-3 sm:p-4 lg:p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded shadow-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                    <div><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
                    <button @click="show = false"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded shadow-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 px-4 py-3 rounded shadow-sm">
                    <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('warning') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@yield('scripts')
</body>
</html>
