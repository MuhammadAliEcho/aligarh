<?php

namespace App\Providers;


use App\StudentResult;
use App\ExamRemark;
use App\StudentAttendance;
use App\InvoiceMaster;

use App\Observers\StudentResultObserver;
use App\Observers\ExamRemarkObserver;
use App\Observers\StudentAttendanceObserver;
use App\Observers\InvoiceMasterObserver;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        StudentResult::observe(StudentResultObserver::class);
        ExamRemark::observe(ExamRemarkObserver::class);
        StudentAttendance::observe(StudentAttendanceObserver::class);
        InvoiceMaster::observe(InvoiceMasterObserver::class);

        // Load custom system configuration
        $this->loadSystemConfiguration();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Dynamically load system configuration from systemInfo.php
     *
     * @return void
     */
    protected function loadSystemConfiguration()
    {
        $smtp = config('systemInfo.general.smtp');
        $general = config('systemInfo.general');

        if (!empty($smtp['host'])) {
            Config::set('mail.driver', 'smtp');

            Config::set('mail.host', $smtp['host']);
            Config::set('mail.port', $smtp['port']);
            Config::set('mail.encryption', $smtp['encryption']);
            Config::set('mail.username', $smtp['username']);
            Config::set('mail.password', $smtp['password']);

            Config::set('mail.from.address', $general['email']);
            Config::set('mail.from.name', $general['name']);
        }
    }
}
