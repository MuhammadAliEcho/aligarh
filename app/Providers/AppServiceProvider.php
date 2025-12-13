<?php

namespace App\Providers;


use App\Model\StudentResult;
use App\Model\ExamRemark;
use App\Model\StudentAttendance;
use App\Model\InvoiceMaster;

use App\Observers\StudentResultObserver;
use App\Observers\ExamRemarkObserver;
use App\Observers\StudentAttendanceObserver;
use App\Observers\InvoiceMasterObserver;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Configure morphMap for polymorphic relationships
        Relation::morphMap([
            'student' => 'App\Model\Student',
            'employee' => 'App\Model\Employee',
            'teacher' => 'App\Model\Teacher',
            'guardian' => 'App\Model\Guardian',
        ]);
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
