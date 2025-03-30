---
# Feel free to add content and custom Front Matter to this file.
# To modify the layout, see https://jekyllrb.com/docs/themes/#overriding-theme-defaults

layout: home
---
## Collection of Google Maps API Web Services for Laravel

Provides a convenient way of setting up and making requests to Google Maps APIs from your [Laravel](http://laravel.com/) application.

For services documentation, API key usage limits, and terms of service, please refer to the official Google Maps documentation:
- [Google Maps API Web Services](https://developers.google.com/maps/documentation/webservices/)
- [Maps API Terms of Service & License Restrictions](https://developers.google.com/maps/terms#section_10_12).

---

### Important Update: Routes API replaces Directions & Distance Matrix

**The Google Maps Directions API and Distance Matrix API are deprecated.**

This package now includes support for the **new Google Maps Routes API**, which is the recommended replacement for calculating routes and route matrices. The Routes API offers enhanced features and performance.

**Please update your application code to use the `routes` and `routematrix` services provided by this package instead of the deprecated `directions` and `distancematrix` services.**

---

## Features
This package provides easy access to the following Google Maps APIs:

- **[Routes API](https://developers.google.com/maps/documentation/routes/route-usecases) (Recommended replacement for Directions & Distance Matrix)**
    - `routes`: Compute routes between locations.
    - `routematrix`: Compute route matrices between origins and destinations.
- [Elevation API](https://developers.google.com/maps/documentation/elevation/)
- [Geocoding API](https://developers.google.com/maps/documentation/geocoding/)
- [Geolocation API](https://developers.google.com/maps/documentation/geolocation/)
- [Roads API](https://developers.google.com/maps/documentation/roads/)
- [Time Zone API](https://developers.google.com/maps/documentation/timezone/)
- [Places API Web Services](https://developers.google.com/places/web-service/)

---

## Dependency

- [PHP cURL](http://php.net/manual/en/curl.installation.php)
- [PHP >= 7.3.0](http://php.net/)


## API Deprecation Notes

In addition to the Directions and Distance Matrix deprecation mentioned above:

**Places API:**
- **Removed Features:** Requests attempting to use Place Add, Place Delete, or Radar Search will receive an error. [More Info](https://cloud.google.com/blog/products/maps-platform/announcing-deprecation-of-place-add)
- **Deprecated Parameters/Fields:**
    - Nearby Search: The `types` parameter is deprecated; use the `type` parameter (string) instead.
    - Place Details: The `reference` field is deprecated; use `placeid` (this package uses `placeid` by default).
    - Place Add & Place Autocomplete: Still use the `types` parameter as per Google's documentation (links provided in the original README).

---

## Installation

Issue following command in console:

```php
composer require alexpechkarev/google-maps
```

## Configuration

Register Service Provider & Facade (in `config/app.php`):

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

Publish configuration file:

```php
php artisan vendor:publish --tag=googlemaps
```

Add API Key: Open **config/googlemaps.php*** and add your Google Maps API key:

```php
/*
|----------------------------------
| Service Keys
|------------------------------------
*/

'key'       => 'ADD YOUR SERVICE KEY HERE',
```

If you like to use different keys for any of the services, you can overwrite master API Key by specifying it in the `service` array for selected web service.

## Usage

General Pattern:
Load the desired service using `\GoogleMaps::load('service-name')`.
Set parameters using `setParam([...])` or `setParamByKey('key', 'value')`.
Execute the request:
- Use `->get()` for all APIs EXCEPT the Routes API.
- Use `->fetch()` ONLY for the Routes API (routes and routematrix services).

#### Example: Geocoding API (using `get()`):
```php
$response = \GoogleMaps::load('geocoding')
		->setParam (['address' =>'santa cruz'])
 		->get();
```

By default, where appropriate, `output` parameter set to `JSON`. Don't forget to decode JSON string into PHP variable.


#### Example: Routes API - Compute Route (using `fetch()`):

Note: The Routes API uses the fetch() method and returns a PHP array directly (no JSON decoding needed).
Note: The config for routes includes a decodePolyline parameter (default true), which adds a decodedPolyline key to the response if a polyline is present.

```php
$routeParams = [
    'origin' => [ /* ... origin details ... */ ],
    'destination' => [ /* ... destination details ... */ ],
    'travelMode' => 'DRIVE',
    // ... other Routes API parameters ...
];

$responseArray = \GoogleMaps::load('routes') // Use 'routes' service
    ->setParam($routeParams)
    ->setFieldMask('routes.duration,routes.distanceMeters,routes.polyline.encodedPolyline') // optional - used to specify fields to return 
    ->fetch(); // Use fetch() for Routes API

// $responseArray is already a PHP array
if (!empty($responseArray['routes'])) {
    // Process the route data
} else {
    // Handle errors or no routes found
}
```

#### Example: Routes API - Compute Route Matrix (using `fetch()`):

```php
$matrixParams = [
    'origins' => [ /* ... array of origins ... */ ],
    'destinations' => [ /* ... array of destinations ... */ ],
    'travelMode' => 'DRIVE',
    // ... other Route Matrix parameters ...
];

$responseArray = \GoogleMaps::load('routematrix') // Use 'routematrix' service
    ->setParam($matrixParams)
    ->setFieldMask('originIndex,destinationIndex,duration,distanceMeters,status,condition') // optional - used to specify fields to return 
    ->fetch(); // Use fetch() for Routes API

// $responseArray is already a PHP array
// Process the matrix data
```

Required parameters can be specified as an array of `key:value` pairs

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




## Available methods

- [`load( $serviceName )`](#load): Loads the specified web service configuration. Returns $this.
- [`setEndpoint( $endpoint )`](#setEndpoint): Sets the desired response format (json or xml) for APIs using get(). Default is json. Not applicable to Routes API (fetch()). Returns $this.
- [`getEndpoint()`](#getEndpoint): Gets the currently configured endpoint format (json or xml).
- [`setParamByKey( $key, $value)`](#setParamByKey): Sets a single request parameter. Use 'dot' notation for nested arrays (e.g., components.country). Returns $this.
- [`setParam( $parameters)`](#setParam): Sets multiple request parameters from an array. Returns $this.
- [`get()`](#get): (**For all APIs EXCEPT Routes API**) Executes the request. Returns a JSON string (or XML string if configured). If $key is provided (using 'dot' notation), attempts to return only that part of the response.
- [`fetch()`](#fetch): (**ONLY for Routes API** - `routes` and `routematrix`) Executes the request against the Routes API. Returns a decoded PHP array directly or throws an `ErrorException`.
- [`containsLocation( $lat, $lng )`](#containsLocation): (**Routes API only**) Checks if a point falls within the polygon returned by a routes API call. Requires a prior `setParam()` call for the route. Returns boolean.
- [`isLocationOnEdge( $lat, $lng, $tolrance)`](#isLocationOnEdge): (**Routes API only**) Checks if a point falls on or near the polyline returned by a routes API call. Requires a prior `setParam()` call for the route. Returns boolean.

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

- `key` - body parameter name
- `value` - body parameter value

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

- **`get()`** - perform web service request (irrespectively to request type POST or GET )
- **`get( $key )`** - accepts string response body key, use 'dot' notation for deeply nested array

This method is not Available for Routes API.

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

<a name="fetch"></a>

- **`fetch()`** - only available for Routes API (whith 'routes' or 'routematrix' services)

This method is ONLY available for Routes API.
Note: config for routes included **decodePolyline** parameter, default **true**. If **true** it will attempts to decode the `polilyne.encodedPolyline` and add `decodePolyline` parameter to the response.

Returns an **array** web service response or thows an **ErrorException**. 
See [Request Body](https://developers.google.com/maps/documentation/routes/reference/rest/v2/TopLevel/computeRoutes#request-body) for details.

```php
$response = \GoogleMaps::load('routes')
                ->setParam($reqRoute) // <-- array see config file for all available parameters or Request Body
                ->fetch();
```

---

<a name="isLocationOnEdge"></a>
**`isLocationOnEdge( $lat, $lng, $tolrance = 0.1 )`** - To determine whether a point falls on or near a polyline, or on or near the edge of a polygon, pass the point, the polyline/polygon, and optionally a tolerance value in degrees.

This method only available with Google Maps Routes API.

Accepted parameter:

- `$lat` - double latitude
- `$lng` - double longitude
- `$tolrance` - double

```php
$response = \GoogleMaps::load('routes')
            ->setParam([
                        'origin' => [
                            'location' => [
                                'latLng' => [
                                    'latitude' => 37.419734,
                                    'longitude' => -122.0827784,
                                ],
                            ],
                        ],
                        'destination' => [
                            'location' => [
                                'latLng' => [
                                    'latitude' => 37.417670,
                                    'longitude' => -122.079595,
                                ],
                            ],
                        ],
                        'travelMode' => 'DRIVE',
                        'routingPreference' => 'TRAFFIC_AWARE',
                        'computeAlternativeRoutes' => false,
                        'routeModifiers' => [
                            'avoidTolls' => false,
                            'avoidHighways' => false,
                            'avoidFerries' => false,
                        ],
                        'languageCode' => 'en-US',
                        'units' => 'IMPERIAL',
                    ])
           ->isLocationOnEdge(37.41665,-122.08175);

    dd( $response  );  // true
```

---

<a name="containsLocation"></a>
**`containsLocation( $lat, $lng )`** -To find whether a given point falls within a polygon.

This method only available with Google Maps Routes API.

Accepted parameter:

- `$lat` - double latitude
- `$lng` - double longitude

```php
$response = \GoogleMaps::load('routes')
            ->setParam([
                        'origin' => [
                            'location' => [
                                'latLng' => [
                                    'latitude' => 37.419734,
                                    'longitude' => -122.0827784,
                                ],
                            ],
                        ],
                        'destination' => [
                            'location' => [
                                'latLng' => [
                                    'latitude' => 37.417670,
                                    'longitude' => -122.079595,
                                ],
                            ],
                        ],
                        'travelMode' => 'DRIVE',
                        'routingPreference' => 'TRAFFIC_AWARE',
                        'computeAlternativeRoutes' => false,
                        'routeModifiers' => [
                            'avoidTolls' => false,
                            'avoidHighways' => false,
                            'avoidFerries' => false,
                        ],
                        'languageCode' => 'en-US',
                        'units' => 'IMPERIAL',
                    ])
           ->containsLocation(37.41764,-122.08293);

    dd( $response  );  // true
```




## Support

[Please open an issue on GitHub](https://github.com/alexpechkarev/google-maps/issues)

## License

Collection of Google Maps API Web Services for Laravel is released under the MIT License. See the bundled
[LICENSE](https://github.com/alexpechkarev/google-maps/blob/master/LICENSE)
file for details.