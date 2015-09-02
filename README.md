# Collection of Google Maps API Web Services for Laravel 5
Provides convenient way of setting up and making requests to Maps API from [**Laravel**] (http://laravel.com/) application. 
For services documentation, API key and Usage Limits visit [**Google Maps API Web Services**] (https://developers.google.com/maps/documentation/webservices/), also check [**Maps API for Terms of Service License Restrictions**] (https://developers.google.com/maps/terms#section_10_12).

Features
------------
*[**Directions API**] (https://developers.google.com/maps/documentation/directions/)

*[**Distance Matrix API**] (https://developers.google.com/maps/documentation/distance-matrix/)

[**Elevation API**] (https://developers.google.com/maps/documentation/elevation/)

[**Geocoding API**] (https://developers.google.com/maps/documentation/geocoding/)

[**Geolocation API**] (https://developers.google.com/maps/documentation/geolocation/)

[**Roads API**] (https://developers.google.com/maps/documentation/roads/)

[**Time Zone API**] (https://developers.google.com/maps/documentation/timezone/)

[**Places API Web Services**] (https://developers.google.com/places/web-service/)


Dependency
------------
[**PHP cURL**] (http://php.net/manual/en/curl.installation.php) required

[**PHP 5**] (http://php.net/)


Installation
------------

Issue following command in console:
```
composer require alexpechkarev/google-maps:dev-master
```

Alternatively  edit composer.json by adding following line and run `composer update`
```php
"require": { 
		....,
		"alexpechkarev/google-maps":"dev-master",
	
	},
```

Configuration
------------

Publish configuration file using `php artisan vendor:publish` or simply copy package configuration file and paste into `config/googlmaps.php`

Open configuration file `config/googlmaps.php` and add your service key
```php
    /*
    |----------------------------------
    | Service Keys
    |------------------------------------
    */
    
    'key'       => 'ADD YOUR SERVICE KEY HERE',
```

If you like to use different keys for any of the services, see `service` array in `config/googlmaps.php` and specify your API Key there.


Register package service provider and facade in 'config/app.php'

```php
'providers' => [
    ...
    GoogleMaps\ServiceProvider\GoogleMapsServiceProvider,
]

'aliases' => [
    ...
    'GoogleMaps' => ' GoogleMaps\Facade\GoogleMapsFacade',
]
```

Usage
------------

Here is an example of making request to Geocoding API:
```php
$response = \GoogleMaps::load('geocoding')
		->setParam (['address' =>'santa cruz'])
 		->get();
```

By default, where appropriate, `output` parameter set to `JSON`. Don't forget to decode JSON string into PHP variable. 
See [**Processing Response**] (https://developers.google.com/maps/documentation/webservices/#Parsing) for more details on parsing returning output.

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
              ->setParamByKey('address', 'santa cruz')
              ->setParamByKey('components.administrative_area', 'TX')
              ->setParamByKey('components.country', 'US')
              ->get();
```

Another example showing request to Places API Place Add service:

```php
$response = \GoogleMaps::load('placeadd')
          ->setParam([
             'location' => [
                'lat' => -33.8669710,
                'lng' => 151.1958750
              ],
             'accuracy' => 0,
             "name" =>  "Google Shoes!",
             "phone_number" =>  "(02) 9374 4000",
             "address" => "48 Pirrama Road, Pyrmont, NSW 2009, Australia",
             "types" => ["shoe_store"],
              "website"=> "http://www.google.com.au/",
              "language"=> "en-AU"                        
                    ])
            ->get();	
```

Available methods
------------

`**load( string 'service name' )**` - load web service by name 

Accepts string as parameter, web service name as specified in configuration file.
Returns instance of WebService class, method is chainable.
```
\GM::load('geocoding')  
\GM::load('directions') 
```

`setEndpoint( string 'endpoint' )` - set request output

Accepts string as parameter, `json` or `xml`, if omitted defaulted to `json`.
Returns reference to the same object, method is  chainable.
```
$response = \GM::load('geocoding')
		->setEndpoint('json')  // return $this
		...
```


`getEndpoint()` - get current request output

Returns string.
```
$endpoint = \GM::load('geocoding')
		->setEndpoint('json')
		->getEndpoint();

echo $endpoint; // output 'json'
```

`setParamByKey( string 'key', string 'value')` - set request parameter using key:value pair

Accepts two parameters:
`key` - body parameter name
`value` - body parameter value 

Deeply nested array can use 'dot' notation to assign value.

Returns reference to the same object, method is  chainable.
```
$endpoint = \GM::load('geocoding')
   ->setParamByKey('address', 'santa cruz')
    ->setParamByKey('components.administrative_area', 'TX') //return $this
    ...
```

`setParam( array 'parameters')` - set all request parameters at once

Accepts array of parameters 

Returns reference to the same object, method is chainable.
```
$response = \GM::load('geocoding')
               ->setParam([
                  'address' => 'santa cruz',
                   'components' => [
                       'administrative_area'   => 'TX',
                       'country'               => 'US',
                        ]
                    ]) // return $this
...
```

`get()` - perform GET web service request

Returns web service response in the format specified by `setEndpoint()` method, if omitted defaulted to `JSON`. 
Use `json_decode()` to convert JSON string into PHP variable. See [**Processing Response**] (https://developers.google.com/maps/documentation/webservices/#Parsing) for more details on parsing returning output.
```
$response = \GM::load('geocoding')
               ->setParam([
                  'address' => 'santa cruz',
                   'components' => [
                       'administrative_area'   => 'TX',
                       'country'               => 'US',
                        ]
                    ]) 
		->get();

var_dump( json_decode( $response ) );  // output 
```

`getByKey( string 'key' = false, int 'offset' = 0, int 'length' = null)` - perform GET web service request and attempts to return value for given key. 
Will return full response object in case when `status` field is not equal `OK`.

Accepts three parameters:
`key` - body parameter name
`offset`  and `length` parameters works as per [**array_slice()**] (http://php.net/manual/en/function.array-slice.php) and returns the sequence of elements from the array as specified by the offset and length parameters.

Deeply nested array can use 'dot' notation to retrieve value.
```
$response = \GM::load('geocoding')
   ->setParamByKey('address', 'santa cruz')
    ->setParamByKey('components.administrative_area', 'TX')
	->getByKey('results.geometry.location');

var_dump( json_decode( $response ) );  
```

`post( array 'parameters')` - perform `POST` web service request with given parameters.

Accepts three parameters:
`parameters`- body parameters 

```
$response = \GM:: load('placeadd')              
        ->post([
            'location' => [
              'lat' => -33.8669710,
              'lng' => 151.1958750
             ],
        'accuracy' => 0,
        "name" =>  "Google Shoes!",
        "phone_number" =>  "(02) 9374 4000",
        "address" => "48 Pirrama Road, Pyrmont, NSW 2009, Australia",
        "types" => ["shoe_store"],
        "website"=> "http://www.google.com.au/",
        "language"=> "en-AU"                        
]);

var_dump( json_decode( $response )) // output JSON response
```
