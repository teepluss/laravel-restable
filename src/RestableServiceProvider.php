<?php namespace Teepluss\Restable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\ResponseFactory;

class RestableServiceProvider extends ServiceProvider {

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
        $configPath = __DIR__.'/../config/restable.php';

        // Publish config.
        $this->publishes([$configPath => config_path('restable.php')], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__.'/../config/restable.php';

        // Merge config to allow user overwrite.
        $this->mergeConfigFrom($configPath, 'restable');

        // $this->app['restable'] = $this->app->share(function($app)
        // {
        //     $response = $app['Illuminate\Contracts\Routing\ResponseFactory'];

        //     return new Restable($app['config'], $response, new Format);
        // });

        $this->app->singleton('Teepluss\Restable\Contracts\Restable', function($app)
        {
            $response = $app['Illuminate\Contracts\Routing\ResponseFactory'];

            return new Restable($app['config'], $response, new Format);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('Teepluss\Restable\Contracts\Restable');
    }

}