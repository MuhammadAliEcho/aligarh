<?php

namespace App\Listeners;

use Stancl\Tenancy\Events\TenancyBootstrapped;
use Illuminate\Support\Facades\Config;

class LoadTenantSystemConfig
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TenancyBootstrapped $event)
    {

        $tenant = $event->tenancy->tenant;

        // Get default system info and tenant system info
        $defaultSystemInfo = config('systemInfo', []);
        $tenantSystemInfo = $tenant->system_info ?? [];

        // Merge tenant settings over default settings recursively
        $systemInfo = array_merge_recursive_distinct($defaultSystemInfo, $tenantSystemInfo);

        if($tenant->id){
            config([
                'cache.prefix'	=>	$tenant->id?? env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache'),
                'cache.stores.file.path' => storage_path('framework/cache/data'.($tenant->id? '/'.$tenant->id : '')),
            ]);
        }

        if (!$systemInfo || empty($systemInfo['smtp']['host'])) {
            return;
        }

        $smtp = $systemInfo['smtp'];
        $general = $systemInfo['general'];

        Config::set('mail.driver', $smtp['mailer']);
        Config::set('mail.host', $smtp['host']);
        Config::set('mail.port', $smtp['port']);
        Config::set('mail.encryption', $smtp['encryption']);
        Config::set('mail.username', $smtp['username']);
        Config::set('mail.password', $smtp['password']);
        Config::set('mail.from.address', $smtp['from_address']);
        Config::set('mail.from.name', $general['title']);

    }


    /**
     * Dynamically load system configuration from systemInfo.php
     *
     * @return void
     */
    // will remove never use anywhere
    protected function loadSystemConfiguration()
    {

        $smtp = tenancy()->tenant->system_info['general']['smtp'];
        $general = tenancy()->tenant->system_info['general'];

        if (!empty($smtp['host']) && !empty($general)) {
            Config::set('mail.driver', 'smtp');

            Config::set('mail.host', $smtp['host']);
            Config::set('mail.port', $smtp['port']);
            Config::set('mail.encryption', $smtp['encryption']);
            Config::set('mail.username', $smtp['username']);
            Config::set('mail.password', $smtp['password']);

            Config::set('mail.from.address', $general['contact_email']);
            Config::set('mail.from.name', $general['name']);
        }
    }

}
