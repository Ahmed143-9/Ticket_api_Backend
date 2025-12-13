<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Configuration\MembersInterface', 'App\Repositories\Configuration\MembersRepository');
        $this->app->bind('App\Contracts\Configuration\SuccessStoriesInterface', 'App\Repositories\Configuration\SuccessStoriesRepository');
        $this->app->bind('App\Contracts\Configuration\ProductInterface', 'App\Repositories\Configuration\ProductRepository');
        $this->app->bind('App\Contracts\Configuration\HomeMainSliderInterface', 'App\Repositories\Configuration\HomeMainSliderRepository');
        $this->app->bind('App\Contracts\Configuration\HomeAdsInterface', 'App\Repositories\Configuration\HomeAdsRepository');
        $this->app->bind('App\Contracts\CartInterface', 'App\Repositories\CartRepository');
        $this->app->bind('App\Contracts\Configuration\EventsInterface', 'App\Repositories\Configuration\EventsRepository');
        $this->app->bind('App\Contracts\Configuration\EventTicketTypeInterface', 'App\Repositories\Configuration\EventTicketTypeRepository');
        $this->app->bind('App\Contracts\Configuration\CorporateServiceInterface', 'App\Repositories\Configuration\CorporateServiceRepository');
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        //
    }
}
