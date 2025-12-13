<?php
namespace App\Providers;
use App\Facades\Modules\SMS\SMSHelper;
use Illuminate\Support\ServiceProvider;
use App\Facades\Modules\Dashboard\DashboardHelper;

class FacadesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('dashboardhelper', function () {  return new DashboardHelper(); });
        $this->app->bind('smshelper', function () {  return new SMSHelper(); });
    }


    public function boot()
    {
        //
    }
}
