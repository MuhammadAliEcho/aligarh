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
use Illuminate\Support\Facades\URL;

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

        if (config('app.ssl')) { 
            URL::forceScheme('https');
        }
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

}
