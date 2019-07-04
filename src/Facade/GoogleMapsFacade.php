<?php namespace GoogleMaps\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @see \GoogleMaps\GoogleMaps
 */
class GoogleMapsFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'GoogleMaps'; }

}
