## Collection of Google Maps API Web Services for Laravel

Provides convenient way of setting up and making requests to Maps API from [Laravel](http://laravel.com/) application.
For services documentation, API key and Usage Limits visit [Google Maps API Web Services](https://developers.google.com/maps/documentation/webservices/) and [Maps API for Terms of Service License Restrictions](https://developers.google.com/maps/terms#section_10_12).

Features
------------
* [Directions API](https://developers.google.com/maps/documentation/directions/)
* [Distance Matrix API](https://developers.google.com/maps/documentation/distance-matrix/)
* [Elevation API](https://developers.google.com/maps/documentation/elevation/)
* [Geocoding API](https://developers.google.com/maps/documentation/geocoding/)
* [Geolocation API](https://developers.google.com/maps/documentation/geolocation/)
* [Roads API](https://developers.google.com/maps/documentation/roads/)
* [Time Zone API](https://developers.google.com/maps/documentation/timezone/)
* [Places API Web Services](https://developers.google.com/places/web-service/)


Dependency
------------
* [PHP cURL](http://php.net/manual/en/curl.installation.php)
* [PHP >= 7.3.0](http://php.net/)


Notes
------------
[Rmoving Place Add, Delete & Radar Search features](https://cloud.google.com/blog/products/maps-platform/announcing-deprecation-of-place-add)

Requests to the Places API attempting to use these features will receive an error response
* Place Add
* Place Delete
* Radar Search

Deprication notices for Google Places API Web Service that effects Premium data (Zagat), types parameter, id and reference fields.

* Nearby Search - **`types`** parameter depricated, use parameter **`type`** (string)
* Place Details - the **`reference`** is now deprecated in favor of **`placeid`** (**`placeid`** originally used in this package)
* Place Add - still uses **`types`** parameter as per [service documentation](https://developers.google.com/places/web-service/add-place)
* Place Autocomplete - still uses **`types`** parameter as per [service documentation](https://developers.google.com/places/web-service/autocomplete)



Installation
------------

Issue following command in console:

For laravel 6 use `6.0`.

```php
composer require alexpechkarev/google-maps
```

Alternatively  edit composer.json by adding following line and run **`composer update`**
```php
"require": {
		....,
		"alexpechkarev/google-maps":"^8.0",

	},
```

Configuration
------------

Register package service provider and facade in 'config/app.php'

```php
'providers' => [
    ...
    GoogleMaps\ServiceProvider\GoogleMapsServiceProvider::class,
]

'aliases' => [
    ...
    'GoogleMaps' => GoogleMaps\Facade\GoogleMapsFacade::class,
]
```


Publish configuration file using **`php artisan vendor:publish --tag=googlemaps`** or simply copy package configuration file and paste into **`config/googlemaps.php`**

Open configuration file **`config/googlemaps.php`** and add your service key
```php
    /*
    |----------------------------------
    | Service Keys
    |------------------------------------
    */

    'key'       => 'ADD YOUR SERVICE KEY HERE',
```

If you like to use different keys for any of the services, you can overwrite master API Key by specifying it in the `service` array for selected web service.


Usage
------------

Here is an example of making request to Geocoding API:
```php
$response = \GoogleMaps::load('geocoding')
		->setParam (['address' =>'santa cruz'])
 		->get();
```

By default, where appropriate, `output` parameter set to `JSON`. Don't forget to decode JSON string into PHP variable.
See [Processing Response](https://developers.google.com/maps/documentation/webservices/#Parsing) for more details on parsing returning output.


Required parameters can be specified as an array of `key:value` pairs:

```php
$response = \GoogleMaps::load('geocoding')
		->setParam ([
		    'address'    =>'santa cruz',
         	    'components' => [
                     	'administrative_area'  => 'TX',
                     	'country'              => 'US',
                      ]

                ])
                ->get();
 ```

Alternatively parameters can be set using `setParamByKey()` method. For deeply nested array use "dot" notation as per example below.

```php
$endpoint = \GoogleMaps::load('geocoding')
   ->setParamByKey('address', 'santa cruz')
   ->setParamByKey('components.administrative_area', 'TX') //return $this
    ...

```

Another example showing request to Places API Place Add service:

```php
$response = \GoogleMaps::load('placeadd')
                ->setParam([
                   'location' => [
                        'lat'  => -33.8669710,
                        'lng'  => 151.1958750
                      ],
                   'accuracy'           => 0,
                   "name"               =>  "Google Shoes!",
                   "address"            => "48 Pirrama Road, Pyrmont, NSW 2009, Australia",
                   "types"              => ["shoe_store"],
                   "website"            => "http://www.google.com.au/",
                   "language"           => "en-AU",
                   "phone_number"       =>  "(02) 9374 4000"
                          ])
                  ->get();
```

Available methods
------------

* [`load( $serviceName )`](#load)
* [`setEndpoint( $endpoint )`](#setEndpoint)
* [`getEndpoint()`](#getEndpoint)
* [`setParamByKey( $key, $value)`](#setParamByKey)
* [`setParam( $parameters)`](#setParam)
* [`get()`](#get)
* [`get( $key )`](#get)
* [`containsLocation( $lat, $lng )`](#containsLocation)
* [`isLocationOnEdge( $lat, $lng, $tolrance)`](#isLocationOnEdge)

---

<a name="load"></a>
**`load( $serviceName )`** - load web service by name

Accepts string as parameter, web service name as specified in configuration file.
Returns reference to it's self.

```php

\GoogleMaps::load('geocoding')
...

```
---

<a name="setEndpoint"></a>
**`setEndpoint( $endpoint )`** - set request output

Accepts string as parameter, `json` or `xml`, if omitted defaulted to `json`.
Returns reference to it's self.

```php
$response = \GoogleMaps::load('geocoding')
		->setEndpoint('json')  // return $this
		...
```

---

<a name="getEndpoint"></a>
**`getEndpoint()`** - get current request output

Returns string.

```php
$endpoint = \GoogleMaps::load('geocoding')
		->setEndpoint('json')
		->getEndpoint();

echo $endpoint; // output 'json'
```

---

<a name="setParamByKey"></a>
**`setParamByKey( $key, $value )`** - set request parameter using key:value pair

Accepts two parameters:
* `key` - body parameter name
* `value` - body parameter value

Deeply nested array can use 'dot' notation to assign value.
Returns reference to it's self.

```php
$endpoint = \GoogleMaps::load('geocoding')
   ->setParamByKey('address', 'santa cruz')
   ->setParamByKey('components.administrative_area', 'TX') //return $this
    ...
```

---

<a name="setParam"></a>
**`setParam( $parameters)`** - set all request parameters at once

Accepts array of parameters
Returns reference to it's self.

```php
$response = \GoogleMaps::load('geocoding')
                ->setParam([
                   'address'     => 'santa cruz',
                   'components'  => [
                        'administrative_area'   => 'TX',
                        'country'               => 'US',
                         ]
                     ]) // return $this
...
```

---

<a name="get"></a>
* **`get()`** - perform web service request (irrespectively to request type POST or GET )
* **`get( $key )`** - accepts string response body key, use 'dot' notation for deeply nested array

Returns web service response in the format specified by **`setEndpoint()`** method, if omitted defaulted to `JSON`.
Use `json_decode()` to convert JSON string into PHP variable. See [Processing Response](https://developers.google.com/maps/documentation/webservices/#Parsing) for more details on parsing returning output.

```php
$response = \GoogleMaps::load('geocoding')
                ->setParamByKey('address', 'santa cruz')
                ->setParamByKey('components.administrative_area', 'TX')
                 ->get();

var_dump( json_decode( $response ) );  // output

/*
{\n
   "results" : [\n
      {\n
         "address_components" : [\n
            {\n
               "long_name" : "277",\n
               "short_name" : "277",\n
               "types" : [ "street_number" ]\n
            },\n
            ...
*/


```

Example with `$key` parameter

```php
$response = \GoogleMaps::load('geocoding')
                ->setParamByKey('latlng', '40.714224,-73.961452')
                 ->get('results.formatted_address');

var_dump( json_decode( $response ) );  // output

/*
array:1 [▼
  "results" => array:9 [▼
    0 => array:1 [▼
      "formatted_address" => "277 Bedford Ave, Brooklyn, NY 11211, USA"
    ]
    1 => array:1 [▼
      "formatted_address" => "Grand St/Bedford Av, Brooklyn, NY 11211, USA"
    ]
            ...
*/


```

---

<a name="isLocationOnEdge"></a>
**`isLocationOnEdge( $lat, $lng, $tolrance = 0.1 )`** - To determine whether a point falls on or near a polyline, or on or near the edge of a polygon, pass the point, the polyline/polygon, and optionally a tolerance value in degrees.

This method only available with Google Maps Directions API.

Accepted parameter:
* `$lat` - double latitude
* `$lng` - double longitude
* `$tolrance` - double

```php
$response = \GoogleMaps::load('directions')
            ->setParam([
                'origin'          => 'place_id:ChIJ685WIFYViEgRHlHvBbiD5nE',
                'destination'     => 'place_id:ChIJA01I-8YVhkgRGJb0fW4UX7Y',
            ])
           ->isLocationOnEdge(55.86483,-4.25161);

    dd( $response  );  // true
```

---


<a name="containsLocation"></a>
**`containsLocation( $lat, $lng )`** -To find whether a given point falls within a polygon.

This method only available with Google Maps Directions API.

Accepted parameter:
* `$lat` - double latitude
* `$lng` - double longitude

```php
$response = \GoogleMaps::load('directions')
            ->setParam([
                'origin'          => 'place_id:ChIJ685WIFYViEgRHlHvBbiD5nE',
                'destination'     => 'place_id:ChIJA01I-8YVhkgRGJb0fW4UX7Y',
            ])
           ->containsLocation(55.86483,-4.25161);

    dd( $response  );  // true
```

Support
-------

[Please open an issue on GitHub](https://github.com/alexpechkarev/google-maps/issues)


License
-------

Collection of Google Maps API Web Services for Laravel is released under the MIT License. See the bundled
[LICENSE](https://github.com/alexpechkarev/google-maps/blob/master/LICENSE)
file for details.
