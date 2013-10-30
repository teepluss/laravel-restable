<?php namespace Teepluss\Restable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
		$this->package('teepluss/restable');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerRestable();
	}

	protected function registerRestable()
	{
		$this->app['restable'] = $this->app->share(function($app)
		{
			$response = new Response;

			$converter = new Format;

			return new Restable($app['config'], $response, $converter);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}