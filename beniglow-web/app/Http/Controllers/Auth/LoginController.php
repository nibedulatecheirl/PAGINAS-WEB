<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $this->ensureIsNotRateLimited($request);

        $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginField => $request->username,
            'password' => $request->password,
            'activo' => true,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            RateLimiter::clear($this->throttleKey($request));
            $request->session()->regenerate();
            $user = Auth::user();
            $user->update(['ultimo_login' => now()]);

            return redirect()->intended(route('dashboard'))
                ->with('success', '¡Bienvenido ' . $user->name . '!');
        }

        RateLimiter::hit($this->throttleKey($request), 60);

        return back()->withErrors([
            'username' => 'Las credenciales son incorrectas o el usuario está inactivo.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }

    private function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => "Demasiados intentos. Intenta nuevamente en {$seconds} segundos.",
        ]);
    }

    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower((string) $request->input('username')) . '|' . $request->ip());
    }
}
