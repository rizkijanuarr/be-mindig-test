<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Models\SiteSetting;
use App\Models\User;
use App\Observers\UserObserver;
 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Apply dynamic site settings (safe + cached)
        try {
            if (app()->runningInConsole()) {
                return; // avoid during artisan operations like migrate/seed
            }

            // Register observers
            try {
                if (Schema::hasTable('users')) {
                    User::observe(UserObserver::class);
                }
            } catch (\Throwable $e) {
                // ignore observer registration errors
            }

            if (! Schema::hasTable('site_settings')) {
                return;
            }

            $setting = Cache::remember('site_setting:active', 600, function () {
                return SiteSetting::query()
                    ->where('is_active', true)
                    ->latest('id')
                    ->first();
            });

            if ($setting) {
                // App name
                config(['app.name' => $setting->app_name]);
                // Locale
                app()->setLocale($setting->locale);
                config(['app.fallback_locale' => $setting->fallback_locale]);

                // Brand (for Filament & frontend)
                $brandName = $setting->brand_name ?: $setting->app_name;
                $brandLogoUrl = $setting->brand_logo ? Storage::url($setting->brand_logo) : null;
                config([
                    'app.brand_name' => $brandName,
                    'app.brand_logo_url' => $brandLogoUrl,
                ]);
            }

            
        } catch (\Throwable $e) {
            // Silently ignore to prevent boot failures
        }
    }
}
