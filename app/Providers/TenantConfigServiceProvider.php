<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class TenantConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
		config([
			'cache.prefix'	=>	self::cachePrefix()?? env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache'),
			'cache.stores.file.path' => storage_path('framework/cache/data'.(self::cachePrefix()? '/'.self::cachePrefix() : '')),
		]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

	public static function cachePrefix(){
		if(isset($_SERVER['HTTP_HOST'])){
			return	$_SERVER['HTTP_HOST'];
		}
		return null;
	}
}
