<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Colegio — Iniciar Sesión</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background:
                linear-gradient(120deg, rgba(255,255,255,.08) 0 18%, transparent 18% 100%),
                linear-gradient(135deg, #102033 0%, #1c3a55 52%, #315f8f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 22px;
        }
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px),
                linear-gradient(180deg, rgba(255,255,255,.035) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: linear-gradient(135deg, transparent 0%, #000 26%, #000 74%, transparent 100%);
        }
        body::after {
            content: '';
            position: absolute;
            inset: auto 0 0 0;
            height: 42%;
            background: linear-gradient(180deg, transparent, rgba(8,18,30,.32));
            pointer-events: none;
        }
        .login-card {
            background: #fbfdff;
            border-radius: 16px;
            padding: 42px 38px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 30px 80px rgba(7, 18, 30, 0.34);
            position: relative;
            z-index: 1;
            border: 1px solid rgba(204,216,230,.92);
        }
        .logo-area {
            text-align: center;
            margin-bottom: 36px;
        }
        .logo-circle {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #18324d, #4f86bd);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 16px 28px -18px rgba(24,50,77,.95);
        }
        .logo-circle i { color: white; font-size: 32px; }
        .logo-area h1 {
            font-size: 22px;
            font-weight: 700;
            color: #18324d;
        }
        .logo-area p {
            font-size: 13px;
            color: #75869a;
            margin-top: 4px;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #4a596b;
            margin-bottom: 8px;
        }
        .input-wrap { position: relative; }
        .input-wrap i {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #75869a;
            font-size: 15px;
        }
        .input-wrap input {
            width: 100%;
            min-height: 46px;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #ccd8e6;
            border-radius: 10px;
            font-size: 15px;
            color: #142033;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
            background: #f7fafd;
        }
        .input-wrap input:focus {
            border-color: #4f86bd;
            box-shadow: 0 0 0 4px rgba(79,134,189,0.18);
            background: #fff;
        }
        .input-wrap input.is-invalid { border-color: #c2414b; }
        .error-msg {
            color: #c2414b;
            font-size: 12px;
            margin-top: 5px;
        }
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .remember-row label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #66758a;
            cursor: pointer;
        }
        .remember-row input[type="checkbox"] { accent-color: #315f8f; }
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #18324d, #4f86bd);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            letter-spacing: .5px;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 26px -18px rgba(24,50,77,.95);
        }
        .alert-error {
            background: #fde8ea;
            border: 1px solid #f3b9bf;
            color: #982f39;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .footer-text {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #66758a;
        }
        .demo-access {
            background: #f7fafd;
            border: 1px solid #ccd8e6;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #526174;
        }
        .demo-access strong { color: #254f79; }
        .demo-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding: 4px 0;
            border-bottom: 1px dashed #ccd8e6;
        }
        .demo-row:last-child { border-bottom: none; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="logo-area">
        <div class="logo-circle">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h1>CRM Colegio</h1>
        <p>Sistema de Gestión Escolar</p>
    </div>

    @if ($errors->any())
    <div class="alert-error">
        <i class="fas fa-exclamation-circle"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <div class="demo-access">
        <div style="font-weight:700;margin-bottom:6px;color:#0f172a;">Accesos de prueba</div>
        <div class="demo-row"><strong>Admin</strong><span>admin@colegio.edu.pe / admin123</span></div>
        <div class="demo-row"><strong>Docente</strong><span>docente@colegio.edu.pe / admin123</span></div>
        <div class="demo-row"><strong>Estudiante</strong><span>estudiante@colegio.edu.pe / admin123</span></div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label>Correo Electrónico</label>
            <div class="input-wrap">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="admin@colegio.edu.pe"
                    value="{{ old('email') }}"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                    autocomplete="email" required>
            </div>
            @error('email') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="••••••••"
                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                    autocomplete="current-password" required>
            </div>
            @error('password') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="remember-row">
            <label>
                <input type="checkbox" name="remember"> Recordar sesión
            </label>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
        </button>
    </form>

    <div class="footer-text">
        CRM Colegio &copy; {{ date('Y') }} — Sistema Educativo
    </div>
</div>
</body>
</html>

