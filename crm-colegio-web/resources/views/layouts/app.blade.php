<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — CRM Colegio</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

    <style>
        :root {
            --sidebar-w: 280px;
            --topbar-h: 70px;
            --primary:   #315f8f;
            --primary-l: #4f86bd;
            --primary-d: #18324d;
            --secondary: #6b7a90;
            --success:   #168a68;
            --warning:   #c47a16;
            --danger:    #c2414b;
            --info:      #2b7fa8;
            --bg:        #eef3f8;
            --card:      #fbfdff;
            --field:     #f7fafd;
            --text:      #142033;
            --muted:     #66758a;
            --border:    #ccd8e6;
            --radius:    10px;
            --ring:      rgba(79, 134, 189, 0.18);
            --shadow:    0 18px 42px -30px rgb(24 50 77 / 0.55);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            color-scheme: light;
        }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background:
                linear-gradient(180deg, rgba(255,255,255,.62), rgba(255,255,255,0) 220px),
                var(--bg);
            color: var(--text);
            min-height: 100vh;
            font-size: 15px;
            line-height: 1.45;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background:
                linear-gradient(160deg, rgba(255,255,255,.13) 0%, rgba(255,255,255,0) 34%),
                linear-gradient(180deg, #102033 0%, #1c3a55 48%, #315f8f 100%);
            color: white;
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
            transition: transform .3s;
            border-right: 1px solid rgba(255,255,255,.14);
            box-shadow: 12px 0 34px -32px rgba(12, 24, 38, .9);
        }
        .sidebar-header {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .profile-avatar {
            width: 52px; height: 52px;
            border-radius: 14px;
            background: linear-gradient(145deg, rgba(255,255,255,.26), rgba(255,255,255,.08));
            border: 1px solid rgba(255,255,255,.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 12px;
        }
        .profile-name {
            font-size: 14px;
            font-weight: 700;
            color: white;
        }
        .profile-role {
            font-size: 11px;
            color: rgba(255,255,255,0.6);
            margin-top: 2px;
        }
        .profile-stats {
            display: flex;
            gap: 16px;
            margin-top: 12px;
        }
        .profile-stat {
            text-align: center;
        }
        .profile-stat span {
            display: block;
            font-size: 15px;
            font-weight: 700;
        }
        .profile-stat small {
            font-size: 10px;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
        }

        .sidebar-menu {
            padding: 16px 0;
            flex: 1;
        }
        .menu-section {
            padding: 12px 20px 4px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.52);
            font-weight: 600;
        }
        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: rgba(255,255,255,0.76);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all .2s;
            border-left: 3px solid transparent;
            position: relative;
        }
        .menu-item:hover {
            background: rgba(255,255,255,0.10);
            color: white;
            border-left-color: rgba(175,211,240,0.86);
        }
        .menu-item.active {
            background: linear-gradient(90deg, rgba(255,255,255,.18), rgba(255,255,255,.07));
            color: white;
            border-left-color: #9dccf4;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.06);
        }
        .menu-item i { width: 18px; text-align: center; font-size: 15px; }
        .badge-count {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 10px;
            padding: 2px 7px;
            border-radius: 10px;
            font-weight: 700;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .dark-mode-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: rgba(255,255,255,0.6);
            padding: 8px 0;
        }

        /* ── MAIN ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: var(--topbar-h);
        }

        /* ── TOPBAR ── */
        .topbar {
            background:
                linear-gradient(180deg, rgba(255,255,255,.98) 0%, rgba(247,250,253,.96) 100%);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: var(--topbar-h);
            min-height: var(--topbar-h);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            margin: 0;
            z-index: 120;
            box-shadow: 0 14px 34px -34px rgba(24,50,77,.75);
            backdrop-filter: blur(12px);
            flex-shrink: 0;
            transition: background .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .topbar.is-scrolled {
            background: rgba(251,253,255,.98);
            border-bottom-color: #b9cadd;
            box-shadow: 0 16px 34px -28px rgba(24,50,77,.75);
        }
        .topbar::after {
            content: '';
            position: absolute;
            left: 28px;
            right: 28px;
            bottom: -1px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(79,134,189,.42), transparent);
            pointer-events: none;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }
        .sidebar-toggle {
            display: none;
            width: 42px; height: 42px;
            border-radius: 10px;
            background: var(--field);
            border: 1px solid var(--border);
            cursor: pointer;
            color: var(--primary-d);
            font-size: 18px;
            align-items: center;
            justify-content: center;
        }
        .page-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
            letter-spacing: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .breadcrumb {
            font-size: 12px;
            color: var(--muted);
        }
        .breadcrumb a { color: var(--primary-l); text-decoration: none; }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        .topbar-search {
            position: relative;
        }
        .topbar-search input {
            height: 42px;
            padding: 9px 12px 9px 38px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 13px;
            width: 240px;
            outline: none;
            color: var(--text);
            background: var(--field);
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .topbar-search input:focus {
            border-color: var(--primary-l);
            background: #fff;
            box-shadow: 0 0 0 4px var(--ring);
        }
        .topbar-search i {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 13px;
        }
        .topbar-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--field);
            color: var(--muted);
            border: 1px solid var(--border);
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
            position: relative;
        }
        .topbar-icon:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 8px; height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid white;
        }
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            min-height: 42px;
            padding: 6px 12px 6px 8px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--field);
            transition: background .2s, border-color .2s, box-shadow .2s;
        }
        .topbar-user:hover {
            background: #fff;
            border-color: #b6c8da;
            box-shadow: 0 12px 24px -24px rgba(24,50,77,.75);
        }
        .user-avatar-sm {
            width: 34px; height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-d), var(--primary-l));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }
        .user-name-sm { font-size: 13px; font-weight: 600; }
        .user-role-sm { font-size: 11px; color: var(--muted); }
        .flash-area {
            padding: 0 28px;
            margin-top: 20px;
        }

        /* ── PAGE BODY ── */
        .page-body {
            padding: 28px;
            flex: 1;
        }

        .welcome-banner {
            background:
                linear-gradient(118deg, rgba(255,255,255,.14) 0%, rgba(255,255,255,0) 34%),
                linear-gradient(135deg, var(--primary-d) 0%, var(--primary) 58%, var(--primary-l) 100%);
            border-radius: var(--radius);
            padding: 30px 32px;
            color: white;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 44px -30px rgba(24,50,77,.9);
            border: 1px solid rgba(255,255,255,.14);
        }
        .welcome-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.12) 46%, transparent 72%);
            transform: skewX(-18deg) translateX(36%);
            pointer-events: none;
        }

        /* ── CARDS ── */
        .card {
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border);
        }
        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #f7fafd 100%);
        }
        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .card-body { padding: 22px; }

        /* ── STAT CARDS ── */
        .stat-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 20px 22px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid var(--border);
        }
        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
            flex-shrink: 0;
        }
        .stat-icon.blue   { background: linear-gradient(135deg, #18324d, #4f86bd); }
        .stat-icon.green  { background: linear-gradient(135deg, #0f5f4c, #22a779); }
        .stat-icon.orange { background: linear-gradient(135deg, #8b5615, #d9912c); }
        .stat-icon.red    { background: linear-gradient(135deg, #8f2934, #d85a64); }
        .stat-icon.purple { background: linear-gradient(135deg, #4b3f72, #7e75a8); }
        .stat-icon.cyan   { background: linear-gradient(135deg, #1f5b72, #3ba6c8); }

        .stat-info .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
        }
        .stat-info .stat-label {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
        }
        .stat-change {
            font-size: 11px;
            margin-top: 6px;
            font-weight: 600;
        }
        .stat-change.up { color: var(--success); }
        .stat-change.down { color: var(--danger); }

        /* ── TABLA ── */
        .table-wrapper {
            overflow-x: auto;
            border-radius: inherit;
        }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 14px 16px;
            text-align: left;
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #254f79;
            border-bottom: 1px solid #b6c8da;
            font-weight: 800;
            white-space: nowrap;
            background:
                linear-gradient(180deg, rgba(255,255,255,.62), rgba(255,255,255,0) 42%),
                linear-gradient(180deg, #eef5fb 0%, #dce9f5 100%);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.9), inset 0 -1px 0 rgba(24,50,77,.08);
        }
        thead th:first-child { padding-left: 18px; }
        thead th:last-child { padding-right: 18px; }
        tbody td {
            padding: 13px 16px;
            font-size: 13.5px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        tbody tr:hover { background: #f4f8fc; }
        tbody tr:last-child td { border-bottom: none; }

        /* ── BADGES ── */
        .badge {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            border: 1px solid transparent;
        }
        .badge-success  { background: #e2f6ee; color: #0f6b50; border-color:#b7e8d4; }
        .badge-warning  { background: #fff2dc; color: #8b5615; border-color:#f4d19a; }
        .badge-danger   { background: #fde8ea; color: #982f39; border-color:#f3b9bf; }
        .badge-info     { background: #e3f3fa; color: #1f5b72; border-color:#b9ddea; }
        .badge-secondary{ background: #eef3f8; color: #526174; border-color:#d8e2ed; }
        .badge-primary  { background: #e5eef8; color: #254f79; border-color:#c2d7ea; }

        /* ── BOTONES ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, background .18s ease;
            min-height: 40px;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-d), var(--primary-l));
            color: white;
            border: 1px solid rgba(255,255,255,.10);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 12px 24px -18px rgba(24,50,77,.9); }
        .btn-secondary { background: #f6f9fc; color: var(--primary-d); border: 1.5px solid var(--border); }
        .btn-secondary:hover { background:#fff; border-color:#b6c8da; }
        .btn-danger    { background: #fde8ea; color: #982f39; border: 1px solid #f3b9bf; }
        .btn-danger:hover { background:#fbdadd; }
        .btn-success   { background: #e2f6ee; color: #0f6b50; border: 1px solid #b7e8d4; }
        .btn-success:hover { background:#d5f0e5; }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 8px; }
        .btn-icon { padding: 8px; width: 34px; height: 34px; justify-content: center; }

        /* ── FORMULARIOS ── */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #4a596b;
            margin-bottom: 7px;
        }
        .form-control {
            width: 100%;
            min-height: 46px;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 15px;
            color: var(--text);
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
            background: var(--field);
            color-scheme: light;
        }
        .form-control:focus {
            border-color: var(--primary-l);
            box-shadow: 0 0 0 4px var(--ring);
            background: #fff;
        }
        .form-control:hover { border-color: #b6c8da; }
        .form-group:focus-within .form-label { color: var(--primary); }
        textarea.form-control { min-height: 96px; resize: vertical; }
        select.form-control {
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            background-color: var(--field);
            background-image:
                linear-gradient(45deg, transparent 50%, var(--primary-d) 50%),
                linear-gradient(135deg, var(--primary-d) 50%, transparent 50%);
            background-position:
                calc(100% - 20px) calc(50% + 2px),
                calc(100% - 14px) calc(50% + 2px);
            background-size: 6px 6px, 6px 6px;
            background-repeat: no-repeat;
            padding-right: 44px;
        }
        select.form-control:focus { background-color: #fff; }
        select.form-control option {
            background: #fff;
            color: var(--text);
        }
        select.form-control option:checked {
            background: #e5eef8;
            color: var(--primary-d);
        }
        select.form-control:disabled {
            background-color: #eef3f8;
            color: var(--muted);
            opacity: .85;
        }
        .crm-select {
            position: relative;
            width: 100%;
        }
        .crm-native-select {
            position: absolute !important;
            left: 0;
            top: 0;
            width: 1px !important;
            height: 1px !important;
            min-height: 1px !important;
            padding: 0 !important;
            opacity: 0 !important;
            pointer-events: none !important;
            border: 0 !important;
        }
        .crm-select-trigger {
            width: 100%;
            min-height: 46px;
            padding: 12px 44px 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background:
                linear-gradient(45deg, transparent 50%, var(--primary-d) 50%) calc(100% - 20px) calc(50% + 2px) / 6px 6px no-repeat,
                linear-gradient(135deg, var(--primary-d) 50%, transparent 50%) calc(100% - 14px) calc(50% + 2px) / 6px 6px no-repeat,
                var(--field);
            color: var(--text);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            font: inherit;
            font-size: 15px;
            line-height: 1.35;
            text-align: left;
            transition: border-color .2s, box-shadow .2s, background-color .2s, transform .14s ease;
        }
        .crm-select-trigger:hover {
            border-color: #b6c8da;
            background-color: #fff;
        }
        .crm-select-trigger:focus,
        .crm-select-trigger.is-open {
            outline: none;
            border-color: var(--primary-l);
            box-shadow: 0 0 0 4px var(--ring);
            background-color: #fff;
        }
        .crm-select-trigger.is-invalid {
            border-color: var(--danger);
        }
        .crm-select-trigger:disabled {
            cursor: not-allowed;
            background-color: #eef3f8;
            color: var(--muted);
            opacity: .85;
        }
        .crm-select-placeholder {
            color: #75869a;
        }
        .crm-select-menu {
            position: fixed;
            z-index: 3600;
            padding: 6px;
            background: #fff;
            border: 1.5px solid #b6c8da;
            border-radius: 12px;
            box-shadow: 0 22px 46px -24px rgba(7,18,30,.55);
            overflow: auto;
            opacity: 0;
            transform: translateY(-6px) scale(.985);
            transform-origin: top center;
            transition: opacity .14s ease, transform .14s ease;
        }
        .crm-select-menu.is-open {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        .crm-select-option {
            width: 100%;
            min-height: 38px;
            padding: 9px 11px;
            border: 0;
            border-radius: 8px;
            background: #fff;
            color: var(--text);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            font: inherit;
            font-size: 14px;
            text-align: left;
            transition: background .12s ease, color .12s ease;
        }
        .crm-select-option:hover,
        .crm-select-option:focus {
            outline: none;
            background: #f0f6fc;
            color: var(--primary-d);
        }
        .crm-select-option.is-selected {
            background: #e5eef8;
            color: var(--primary-d);
            font-weight: 700;
        }
        .crm-select-option.is-selected::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 12px;
            color: var(--primary);
        }
        .crm-select-option:disabled {
            cursor: not-allowed;
            color: #8a98aa;
            background: #f7fafd;
        }
        input[type="checkbox"], input[type="radio"] { accent-color: var(--primary); }
        .form-control::placeholder { color: #75869a; }
        .form-control.is-invalid { border-color: var(--danger); }
        .invalid-feedback { color: var(--danger); font-size: 12px; margin-top: 4px; }

        [id^="modal-"] {
            backdrop-filter: blur(4px);
        }
        [id^="modal-"] > div {
            background: var(--card) !important;
            border: 1px solid var(--border);
            border-radius: var(--radius) !important;
            box-shadow: 0 26px 70px -34px rgba(7,18,30,.9) !important;
        }

        /* ── GRID SYSTEM RESPONSIVO ── */
        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-6 { grid-template-columns: repeat(6, 1fr); }

        /* ── RESPONSIVIDAD (MEDIA QUERIES) ── */
        @media (max-width: 1024px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-3 { grid-template-columns: repeat(2, 1fr); }
            .grid-6 { grid-template-columns: repeat(3, 1fr); }
        }

        @media (max-width: 768px) {
            :root { --sidebar-w: 0px; }
            .sidebar {
                transform: translateX(-100%);
                width: 260px;
                position: fixed;
                z-index: 1000;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 900;
                backdrop-filter: blur(4px);
            }
            .sidebar-overlay.show { display: block; }
            
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: flex; }
            
            .grid-2, .grid-3, .grid-4, .grid-6 { grid-template-columns: 1fr; }
            
            .topbar-search { display: none; }
            .topbar { left: 0; padding: 0 16px; }
            .topbar::after { left: 16px; right: 16px; }
            .page-body { padding: 16px; }
            .flash-area { padding: 0 16px; margin-top: 16px; }
            
            .welcome-banner { padding: 24px; text-align: center; }
            .dashboard-stats-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .topbar-user .user-info-sm { display: none; }
            .page-title { font-size: 16px; }
            .btn span { display: none; }
            .btn i { margin: 0; }
        }


        /* ── ALERTS ── */
        .alert {
            padding: 13px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: #e2f6ee; color: #0f6b50; border-left: 4px solid var(--success); }
        .alert-danger  { background: #fde8ea; color: #982f39; border-left: 4px solid var(--danger); }
        .alert-warning { background: #fff2dc; color: #8b5615; border-left: 4px solid var(--warning); }
        .alert-info    { background: #e3f3fa; color: #1f5b72; border-left: 4px solid var(--info); }

        /* ── PAGINACIÓN ── */
        .crm-pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            width: 100%;
        }
        .crm-pagination-summary {
            font-size: 13px;
            color: var(--muted);
            white-space: nowrap;
        }
        .pagination {
            display: flex;
            gap: 6px;
            align-items: center;
            flex-wrap: wrap;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .page-item { display: inline-flex; }
        .page-link {
            min-width: 36px;
            min-height: 36px;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            font-size: 13px;
            color: var(--text);
            text-decoration: none;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            font-weight: 700;
        }
        .page-link:hover {
            background: #f7fafd;
            border-color: #b6c8da;
            color: var(--primary);
        }
        .page-item.active .page-link,
        .page-link.active {
            background: var(--primary-l);
            color: white;
            border-color: var(--primary-l);
            box-shadow: 0 10px 20px -18px rgba(24,50,77,.9);
        }
        .page-item.disabled .page-link {
            opacity: .45;
            pointer-events: none;
            background: #f6f9fc;
            color: var(--muted);
        }
        @media (max-width: 640px) {
            .crm-pagination-wrap { align-items: flex-start; flex-direction: column; }
            .crm-pagination-summary { white-space: normal; }
        }

        @media print {
            body { background: #fff !important; }
            .sidebar, .topbar, .sidebar-overlay, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .main-content { padding-top: 0 !important; }
            .page-body { padding: 0 !important; }
            .card { box-shadow: none !important; border: 1px solid #ccd8e6 !important; }
        }

    </style>
    @stack('styles')
</head>
<body>
@php
    $currentUser = auth()->user();
    $canAccess = fn (string $module) => $currentUser->canAccess($module);
@endphp

<!-- ── SIDEBAR ── -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="profile-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="profile-name">{{ auth()->user()->name }}</div>
        <div class="profile-role">{{ $currentUser->role_label }}</div>
        <div class="profile-stats">
            <div class="profile-stat">
                <span>{{ \App\Models\Alumno::where('estado','activo')->count() }}</span>
                <small>Alumnos</small>
            </div>
            <div class="profile-stat">
                <span>{{ \App\Models\Personal::where('estado','activo')->count() }}</span>
                <small>Personal</small>
            </div>
            <div class="profile-stat">
                <span>{{ \App\Models\Mensaje::where('destinatario_id',auth()->id())->where('leido',false)->count() }}</span>
                <small>Mensajes</small>
            </div>
        </div>
    </div>

    <div class="sidebar-menu">
        <div class="menu-section">Principal</div>

        <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>

        @if($canAccess('grados') || $canAccess('materias') || $canAccess('alumnos') || $canAccess('matriculas') || $canAccess('notas') || ($currentUser->role === 'estudiante' && $currentUser->alumno_id))
            <div class="menu-section">Académico</div>

            @if($canAccess('grados'))
                <a href="{{ route('grados.index') }}" class="menu-item {{ request()->routeIs('grados.*') || request()->routeIs('secciones.*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i> Grados y Sec.
                </a>
            @endif
            @if($canAccess('materias'))
                <a href="{{ route('materias.index') }}" class="menu-item {{ request()->routeIs('materias.*') || request()->routeIs('asignaciones.*') ? 'active' : '' }}">
                    <i class="fas fa-book-open"></i> Materias
                </a>
            @endif
            @if($canAccess('alumnos'))
                <a href="{{ route('alumnos.index') }}" class="menu-item {{ request()->routeIs('alumnos.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate"></i> Alumnos
                </a>
            @endif
            @if($canAccess('matriculas'))
                <a href="{{ route('matriculas.index') }}" class="menu-item {{ request()->routeIs('matriculas.*') ? 'active' : '' }}">
                    <i class="fas fa-file-signature"></i> Matrículas
                </a>
            @endif
            @if($canAccess('notas'))
                <a href="{{ route('notas.index') }}" class="menu-item {{ request()->routeIs('notas.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> Calificaciones
                </a>
            @elseif($currentUser->role === 'estudiante' && $currentUser->alumno_id)
                <a href="{{ route('notas.boleta', $currentUser->alumno_id) }}" class="menu-item {{ request()->routeIs('notas.boleta') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Mi Boleta
                </a>
            @endif
        @endif

        @if($canAccess('pagos') || $canAccess('personal'))
            <div class="menu-section">Administración</div>

            @if($canAccess('pagos'))
                <a href="{{ route('pagos.index') }}" class="menu-item {{ request()->routeIs('pagos.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i> Pagos
                </a>
            @endif
            @if($canAccess('personal'))
                <a href="{{ route('personal.index') }}" class="menu-item {{ request()->routeIs('personal.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Personal
                </a>
            @endif
        @endif

        <div class="menu-section">Comunicación</div>

        <a href="{{ route('mensajes.index') }}" class="menu-item {{ request()->routeIs('mensajes.*') ? 'active' : '' }}">
            <i class="fas fa-envelope"></i> Mensajes
            @php $noLeidos = \App\Models\Mensaje::where('destinatario_id',auth()->id())->where('leido',false)->count(); @endphp
            @if($noLeidos > 0)
                <span class="badge-count">{{ $noLeidos }}</span>
            @endif
        </a>

        @if($canAccess('configuracion') || $canAccess('sistema') || $canAccess('conceptos') || $canAccess('reportes'))
            <div class="menu-section">Configuración</div>

            @if($canAccess('configuracion'))
                <a href="{{ route('configuracion.index') }}" class="menu-item {{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
                    <i class="fas fa-sliders-h"></i> Ajustes del Sistema
                </a>
            @endif
            @if($canAccess('sistema'))
                <a href="{{ route('sistema.index') }}" class="menu-item {{ request()->routeIs('sistema.*') ? 'active' : '' }}">
                    <i class="fas fa-database"></i> Mantenimiento
                </a>
            @endif
            @if($canAccess('conceptos'))
                <a href="{{ route('conceptos.index') }}" class="menu-item {{ request()->routeIs('conceptos.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Conceptos de Pago
                </a>
            @endif
            @if($canAccess('reportes'))
                <a href="{{ route('reportes.index') }}" class="menu-item {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Reportes
                </a>
            @endif
        @endif
    </div>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();"
               class="menu-item" style="border-left:none; color:rgba(255,255,255,0.5);">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </form>
    </div>
</nav>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ── MAIN ── -->
<div class="main-content">
    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" id="btnToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
        </div>
        <div class="topbar-right">
            <div class="topbar-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar...">
            </div>
            <a href="{{ route('mensajes.index') }}" class="topbar-icon" title="Mensajes">
                <i class="fas fa-bell"></i>
                @if(isset($noLeidos) && $noLeidos > 0)
                    <span class="notif-dot"></span>
                @endif
            </a>
            <div class="topbar-user">
                <div class="user-avatar-sm">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div class="user-name-sm">{{ auth()->user()->name }}</div>
                    <div class="user-role-sm">{{ $currentUser->role_label }}</div>
                </div>
            </div>
        </div>
    </header>

    <!-- ALERTS -->
    @if(session('success') || session('error'))
    <div class="flash-area">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i>
                {{ session('error') }}
            </div>
        @endif
    </div>
    @endif

    <!-- PAGE CONTENT -->
    <div class="page-body">
        @yield('content')
    </div>
</div>

<script>
    const btnToggle = document.getElementById('btnToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const topbar = document.querySelector('.topbar');

    function syncTopbarState() {
        if (topbar) {
            topbar.classList.toggle('is-scrolled', window.scrollY > 8);
        }
    }

    syncTopbarState();
    window.addEventListener('scroll', syncTopbarState, { passive: true });

    if(btnToggle) {
        btnToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }

    if(overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    (function () {
        const enhancedSelects = new WeakMap();
        let activeSelect = null;
        let activeMenu = null;

        function selectedText(select) {
            const option = select.options[select.selectedIndex];
            return option ? option.textContent.trim() : '';
        }

        function refreshTrigger(select) {
            const state = enhancedSelects.get(select);
            if (!state) return;

            const label = selectedText(select) || select.options[0]?.textContent.trim() || 'Seleccionar...';
            state.label.textContent = label;
            state.label.classList.toggle('crm-select-placeholder', !select.value);
            state.trigger.disabled = select.disabled;
            state.trigger.setAttribute('aria-expanded', activeSelect === select ? 'true' : 'false');
        }

        function closeMenu() {
            if (!activeMenu || !activeSelect) return;

            const state = enhancedSelects.get(activeSelect);
            if (state) state.trigger.classList.remove('is-open');
            activeMenu.classList.remove('is-open');

            const menuToRemove = activeMenu;
            window.setTimeout(() => menuToRemove.remove(), 140);
            activeMenu = null;
            activeSelect = null;
        }

        function positionMenu(state) {
            if (!activeMenu) return;

            const rect = state.trigger.getBoundingClientRect();
            const gap = 6;
            const viewportGap = 12;
            const below = window.innerHeight - rect.bottom - viewportGap;
            const above = rect.top - viewportGap;
            const openAbove = below < 190 && above > below;
            const maxHeight = Math.max(150, Math.min(320, openAbove ? above - gap : below - gap));

            activeMenu.style.left = rect.left + 'px';
            activeMenu.style.width = rect.width + 'px';
            activeMenu.style.maxHeight = maxHeight + 'px';
            activeMenu.style.top = (openAbove ? rect.top - gap - maxHeight : rect.bottom + gap) + 'px';
            activeMenu.style.transformOrigin = openAbove ? 'bottom center' : 'top center';
        }

        function buildMenu(select) {
            const state = enhancedSelects.get(select);
            const menu = document.createElement('div');
            menu.className = 'crm-select-menu';
            menu.setAttribute('role', 'listbox');

            Array.from(select.options).forEach(option => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'crm-select-option';
                item.textContent = option.textContent.trim() || option.value;
                item.disabled = option.disabled;
                item.setAttribute('role', 'option');
                item.setAttribute('aria-selected', option.selected ? 'true' : 'false');
                item.classList.toggle('is-selected', option.selected);

                item.addEventListener('click', () => {
                    if (option.disabled) return;
                    select.value = option.value;
                    select.dispatchEvent(new Event('input', { bubbles: true }));
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                    refreshTrigger(select);
                    closeMenu();
                    state.trigger.focus({ preventScroll: true });
                });

                menu.appendChild(item);
            });

            return menu;
        }

        function openMenu(select) {
            const state = enhancedSelects.get(select);
            if (!state || select.disabled) return;

            if (activeSelect === select) {
                closeMenu();
                return;
            }

            closeMenu();
            activeSelect = select;
            activeMenu = buildMenu(select);
            document.body.appendChild(activeMenu);
            state.trigger.classList.add('is-open');
            refreshTrigger(select);
            positionMenu(state);

            requestAnimationFrame(() => activeMenu?.classList.add('is-open'));
        }

        function enhanceSelect(select) {
            if (enhancedSelects.has(select) || select.multiple || Number(select.size) > 1) return;

            const originalInlineStyles = {
                width: select.style.width,
                minWidth: select.style.minWidth,
                maxWidth: select.style.maxWidth,
                flex: select.style.flex,
                flexGrow: select.style.flexGrow,
                flexShrink: select.style.flexShrink,
                flexBasis: select.style.flexBasis,
            };

            const wrapper = document.createElement('div');
            wrapper.className = 'crm-select';
            Object.entries(originalInlineStyles).forEach(([property, value]) => {
                if (value) wrapper.style[property] = value;
            });
            if (originalInlineStyles.minWidth && !originalInlineStyles.width) {
                wrapper.style.width = 'auto';
            }
            select.parentNode.insertBefore(wrapper, select);
            wrapper.appendChild(select);
            select.classList.add('crm-native-select');

            const trigger = document.createElement('button');
            trigger.type = 'button';
            trigger.className = 'crm-select-trigger';
            trigger.setAttribute('aria-haspopup', 'listbox');
            trigger.setAttribute('aria-expanded', 'false');

            const label = document.createElement('span');
            trigger.appendChild(label);
            wrapper.appendChild(trigger);

            enhancedSelects.set(select, { wrapper, trigger, label });
            refreshTrigger(select);

            trigger.addEventListener('click', event => {
                event.stopPropagation();
                openMenu(select);
            });

            trigger.addEventListener('keydown', event => {
                if (['Enter', ' ', 'ArrowDown'].includes(event.key)) {
                    event.preventDefault();
                    openMenu(select);
                }
                if (event.key === 'Escape') closeMenu();
            });

            select.addEventListener('change', () => refreshTrigger(select));
            select.addEventListener('focus', () => trigger.focus({ preventScroll: true }));
            select.addEventListener('invalid', () => trigger.classList.add('is-invalid'));

            const observer = new MutationObserver(() => {
                refreshTrigger(select);
                if (activeSelect === select) closeMenu();
            });
            observer.observe(select, { childList: true, subtree: true, attributes: true, attributeFilter: ['disabled', 'selected'] });

            const form = select.closest('form');
            if (form && !form.dataset.crmSelectResetBound) {
                form.dataset.crmSelectResetBound = '1';
                form.addEventListener('reset', () => {
                    window.setTimeout(() => refreshCrmSelects(form), 0);
                });
            }
        }

        function observeModal(modal) {
            if (!modal || modal.dataset.crmSelectObserved) return;
            modal.dataset.crmSelectObserved = '1';

            const observer = new MutationObserver(() => {
                if (modal.style.display && modal.style.display !== 'none') {
                    requestAnimationFrame(() => refreshCrmSelects(modal));
                }
            });
            observer.observe(modal, { attributes: true, attributeFilter: ['style'] });
        }

        function refreshCrmSelects(scope = document) {
            scope.querySelectorAll('select.form-control').forEach(enhanceSelect);
            scope.querySelectorAll('[id^="modal-"]').forEach(observeModal);
            scope.querySelectorAll('select.form-control').forEach(refreshTrigger);
        }

        document.addEventListener('click', event => {
            if (!activeMenu || activeMenu.contains(event.target)) return;
            const state = activeSelect ? enhancedSelects.get(activeSelect) : null;
            if (state?.trigger.contains(event.target)) return;
            closeMenu();
        });

        window.addEventListener('resize', closeMenu);
        document.addEventListener('scroll', event => {
            if (activeMenu && activeMenu.contains(event.target)) return;
            closeMenu();
        }, true);

        window.refreshCrmSelects = refreshCrmSelects;
        refreshCrmSelects();
    })();
</script>
@stack('scripts')
</body>
</html>

