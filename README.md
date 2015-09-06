## Collection of Google Maps API Web Services for Laravel 5
Provides convenient way of setting up and making requests to Maps API from [Laravel] (http://laravel.com/) application. 
For services documentation, API key and Usage Limits visit [Google Maps API Web Services] (https://developers.google.com/maps/documentation/webservices/), also check [Maps API for Terms of Service License Restrictions] (https://developers.google.com/maps/terms#section_10_12).

Features
------------
* [Directions API] (https://developers.google.com/maps/documentation/directions/)
* [Distance Matrix API] (https://developers.google.com/maps/documentation/distance-matrix/)
* [Elevation API] (https://developers.google.com/maps/documentation/elevation/)
* [Geocoding API] (https://developers.google.com/maps/documentation/geocoding/)
* [Geolocation API] (https://developers.google.com/maps/documentation/geolocation/)
* [Roads API] (https://developers.google.com/maps/documentation/roads/)
* [Time Zone API] (https://developers.google.com/maps/documentation/timezone/)
* [Places API Web Services] (https://developers.google.com/places/web-service/)


Dependency
------------
* [PHP cURL] (http://php.net/manual/en/curl.installation.php)
* [PHP 5] (http://php.net/)


Installation
------------

Issue following command in console:

```php
composer require alexpechkarev/google-maps:1.0
```

Alternatively  edit composer.json by adding following line and run **`composer update`**
```php
"require": { 
		....,
		"alexpechkarev/google-maps":"1.0",
	
	},
```

Configuration
------------

Register package service provider and facade in 'config/app.php'

```php
'providers' => [
    ...
    'GoogleMaps\ServiceProvider\GoogleMapsServiceProvider',
]

'aliases' => [
    ...
    'GoogleMaps' => 'GoogleMaps\Facade\GoogleMapsFacade',
]
```


Publish configuration file using **`php artisan vendor:publish --tag=googlemaps --force`** or simply copy package configuration file and paste into **`config/googlmaps.php`**

Open configuration file **`config/googlmaps.php`** and add your service key
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
See [Processing Response] (https://developers.google.com/maps/documentation/webservices/#Parsing) for more details on parsing returning output.

Output parameter can be set using `setEndpoint()` method:

```php
$response = \GoogleMaps::load('geocoding')
 		->setEndpoint('xml')
 		->setParam (['address' =>'santa cruz'])
 		->get();
 ```

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
$response = \GoogleMaps::load('geocoding')

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
* [`getResponseByKey( $key )`](#getResponseByKey)

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
**`get()`** - perform web service request (irrespectively to request type POST or GET )

Returns web service response in the format specified by **`setEndpoint()`** method, if omitted defaulted to `JSON`. 
Use `json_decode()` to convert JSON string into PHP variable. See [Processing Response] (https://developers.google.com/maps/documentation/webservices/#Parsing) for more details on parsing returning output.

```php
$response = \GoogleMaps::load('geocoding')
                ->setParam([
                   'address'    => 'santa cruz',
                   'components' => [
                        'administrative_area'   => 'TX',
                        'country'               => 'US',
                         ]
                     ]) 
                 ->get();

var_dump( json_decode( $response ) );  // output 
```

---

<a name="getResponseByKey"></a>
**`getResponseByKey( $key)`** - perform  web service request and attempts to return value for given key.   
Will return full response object in case when `status` field is not equal `OK`.

Accepted parameter:
* `key` - body parameter name

Deeply nested array can use 'dot' notation to retrieve value.

```php
$response = \GoogleMaps::load('geocoding')
                ->setParamByKey('address', 'santa cruz')
                ->setParamByKey('components.administrative_area', 'TX')
                ->getResponseByKey('results.geometry.location');

var_dump( json_decode( $response ) );  
```


Support
-------

[Please open an issue on GitHub](https://github.com/alexpechkarev/google-maps/issues)


License
-------

Collection of Google Maps API Web Services for Laravel 5 is released under the MIT License. See the bundled
[LICENSE](https://github.com/alexpechkarev/google-maps/blob/master/LICENSE)
file for details.
