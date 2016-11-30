<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SnatchServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBiqugeSnatch();
        $this->registerKanshuzhongSnatch();
        $this->registerMzhuSnatch();
    }

    public function registerBiqugeSnatch()
    {
        $this->app->singleton('biquge', function(){
            return new \App\Repositories\Snatch\Biquge();
        });
    }

    public function registerKanshuzhongSnatch()
    {
        $this->app->singleton('kanshuzhong', function(){
            return new \App\Repositories\Snatch\Kanshuzhong();
        });
    }

    public function registerMzhuSnatch()
    {
        $this->app->singleton('mzhu', function(){
            return new \App\Repositories\Snatch\Mzhu();
        });
    }

    public function provides()
    {
        return ['biquge', 'kanshuzhong', 'mzhu'];
    }
}