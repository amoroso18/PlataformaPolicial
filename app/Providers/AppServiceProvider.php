<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
      
   
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $today = now()->startOfDay();
        $tomorrow = $today->copy()->addDay();
    
        if (Schema::hasTable('auditoria_notificaciones')) {
            $auditoria_notificaciones_day = DB::table('auditoria_notificaciones')
            ->whereBetween('created_at', [$today, $tomorrow])
            ->count();
    
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
    
            $auditoria_notificaciones_monyh = DB::table('auditoria_notificaciones')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
    
        }else{
            $auditoria_notificaciones_day = [];
            $auditoria_notificaciones_monyh = [];
        }

       
        View::share('auditoria_notificaciones_day', $auditoria_notificaciones_day);
        View::share('auditoria_notificaciones_monyh', $auditoria_notificaciones_monyh);
        
    }
}
