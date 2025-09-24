<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class TenantConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Do nothing in CLI context (e.g. php artisan commands)
        if ($this->app->runningInConsole()) {
            return;
        }

        $prefix = self::cachePrefix();

        if ($prefix) {
            config([
                'cache.prefix' => $prefix,
                'cache.stores.file.path' => storage_path('framework/cache/data/' . $prefix),
            ]);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Nothing here for now, can add tenancy events if needed later
    }

    /**
     * Generate cache prefix based on domain (e.g., tenant.example.com).
     *
     * @return string|null
     */
    public static function cachePrefix(): ?string
    {
        // Make sure we're not in CLI and request is available
        if (php_sapi_name() !== 'cli' && isset($_SERVER['HTTP_HOST'])) {
            return Str::slug($_SERVER['HTTP_HOST'], '_');
        }

        return null;
    }
}
