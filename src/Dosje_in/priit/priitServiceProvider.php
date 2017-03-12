<?php namespace Dosje_in\integrations;

use Illuminate\Support\ServiceProvider;

class priitServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        //config publish
        $this->publishes([
            __DIR__.'/../../config/priit.php' => config_path('priit.php'),
            __DIR__.'/../../public/chat.css' => public_path('priit/chat.css')
        ]);

        $this->loadViewsFrom(__DIR__.'/../../views', 'priit');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

}