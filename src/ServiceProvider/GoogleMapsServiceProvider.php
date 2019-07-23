<?php namespace GoogleMaps\ServiceProvider;

use GoogleMaps\GoogleMaps;
use Illuminate\Support\ServiceProvider;

class GoogleMapsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->publishes([
            __DIR__.'/../config/googlemaps.php' => config_path('googlemaps.php'),
        ], 'googlemaps');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
    {
        $this->app->bind('GoogleMaps', function(){
            return new GoogleMaps;
        });
	}
        /***/

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
        return array('GoogleMaps');
	}






}
