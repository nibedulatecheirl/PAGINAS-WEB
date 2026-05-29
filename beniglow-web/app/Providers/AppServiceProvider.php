<?php

namespace App\Providers;

use App\Models\Configuracion;
use App\Models\Empresa;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('storefront-orders', function (Request $request) {
            return [
                Limit::perMinute(5)->by($request->ip()),
                Limit::perHour(30)->by($request->ip()),
            ];
        });

        View::composer('*', function ($view) {
            try {
                $empresa = Empresa::first();
                $config = Configuracion::pluck('valor', 'clave')->toArray();
                $view->with('empresaGlobal', $empresa)
                     ->with('configGlobal', $config);
            } catch (\Exception $e) {
                $view->with('empresaGlobal', null)
                     ->with('configGlobal', []);
            }
        });
    }
}
