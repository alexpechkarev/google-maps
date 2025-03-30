<?php

namespace App\Http\Controllers;



class ExampleController extends Controller
{

    /*
	|--------------------------------------------------------------------------
	| Example Controller
	|--------------------------------------------------------------------------
	|
	| This controller illustrate how to make Google Maps API Web Service 
        | calls using this package
        | 
        |  Place this file within your controllers app/Http/Controllers
        |  In the routes.php file add Route::controller('example', 'TestController');
        |  Make request by accessing each method in web browser:
        |  http://test.net/example/geocoding
        |  http://test.net/example/directions
	|
	*/




    public function getGeocoding()
    {
        /**
         * Usin Place Id
         */

        $d['a'] = \GoogleMaps::load('geocoding')
            ->setParamByKey('place_id', 'ChIJd8BlQ2BZwokRAFUEcm_qrcA')
            ->get();


        /**
         * Reverse geocoding with components parameters
         */
        $d['b'] = \GoogleMaps::load('geocoding')
            > setParamByKey('latlng', '40.714224,-73.961452')
            ->setParamByKey('components.administrative_area', 'TX')
            ->setParamByKey('components.country', 'US')
            ->get();

        /**
         * Setting all parameters at once and retrieve locations parameters from response 
         */
        $d['c'] = \GoogleMaps::load('geocoding')

            ->setParam([
                'address' => 'santa cruz',
                'components'    => [
                    'administrative_area'   => 'TX',
                    'country'               => 'US',
                ]
            ])
            ->getResponseByKey('results.geometry.location');


        dd($d);
    }

    public function getRoutes()
    {
        $reqRoute = [
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
        ];
        $r = \GoogleMaps::load('routes')
            ->setParam($reqRoute)
            #->setFieldMask('routes.duration,routes.distanceMeters,routes.polyline.encodedPolyline') // optional - specify which fields to return
            ->fetch();
        #->isLocationOnEdge(37.41665,-122.08175);
        #->containsLocation(37.41764,-122.08293);

        dd($r);
    }

    public function getRouteMatrix()
    {

        $reqRouteMatrix = [
            'origins' => [
                [
                    'waypoint' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => 37.420761,
                                'longitude' => -122.081356,
                            ],
                        ],
                    ],
                ],
                [
                    'waypoint' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => 37.403184,
                                'longitude' => -122.097371,
                            ],
                        ],
                    ],
                ],
            ],
            'destinations' => [
                [
                    'waypoint' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => 37.420999,
                                'longitude' => -122.086894,
                            ],
                        ],
                    ],
                ],
                [
                    'waypoint' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => 37.383047,
                                'longitude' => -122.044651,
                            ],
                        ],
                    ],
                ],
            ],
            // Optional parameters
            "travelMode" => "DRIVE",
            "routingPreference" => "TRAFFIC_AWARE"
        ];
        $r = \GoogleMaps::load('routematrix')
            ->setParam($reqRouteMatrix)
            ->setFieldMask('originIndex,destinationIndex,duration,distanceMeters,status,condition') // optional - specify which fields to return
            ->fetch();


        dd($r);
    }

    public function getElevation()
    {

        $d['a'] =  \GoogleMaps::load('elevation')
            ->setParamByKey('locations', '39.7391536,-104.9847034') // can be given as an array ['36.578581,-118.291994', '36.23998,-116.83171']
            ->get();

        $d['b'] =  \GoogleMaps::load('elevation')
            ->setParamByKey('path', ['40.714728,-73.998672', '-34.397,150.644'])
            ->setParamByKey('samples', 3)
            ->get();

        $d['c'] =  \GoogleMaps::load('elevation')
            ->setParamByKey('path', 'enc:gfo}EtohhUxD@bAxJmGF')
            ->setParamByKey('samples', 3)
            ->getResponseByKey('results.elevation');

        dd($d);
    }



    public function getGeolocation()
    {



        $data = [
            'homeMobileCountryCode' => 310,
            'homeMobileNetworkCode' => 260,
            'radioType'             => "gsm",
            'carrier'               => "T-Mobile",
            'cellTowers' => [
                'cellId'            => 39627456,
                'locationAreaCode'  => 40495,
                'mobileCountryCode' => 310,
                'mobileNetworkCode' => 260,
                'age'               => 0,
                'signalStrength'    => -95,
            ],
            'wifiAccessPoints' => [
                [
                    'macAddress'        => "01:23:45:67:89:AB",
                    'signalStrength'    => 8,
                    'age'               => 0,
                    'channel'           => 8,
                    'signalToNoiseRatio' => -65,
                ],
                [
                    'macAddress'        => "01:23:45:67:89:AC",
                    'signalStrength'    => 4,
                    'age'               => 0,
                ],
            ],


        ];

        $d['a'] =  \GoogleMaps::load('geolocate')
            ->setParam($data)
            ->get();


        $d['b'] =  \GoogleMaps::load('geolocate')
            ->setParamByKey('homeMobileCountryCode', 310)
            ->setParamByKey('homeMobileNetworkCode', 260)
            ->setParamByKey('radioType', 'gsm')
            ->setParamByKey('carrier', 'T-Mobile')
            ->setParamByKey('cellTowers', ['cellId' => 39627456])
            ->setParamByKey('cellTowers', ['locationAreaCode' => 40495])
            ->setParamByKey('cellTowers', ['mobileCountryCode' => 310])
            ->setParamByKey('cellTowers', ['mobileNetworkCode' => 260])
            ->setParamByKey('cellTowers', ['age' => 0])
            ->setParamByKey('wifiAccessPoints', ['macAddress' => '01:23:45:67:89:AB'])
            ->setParamByKey('wifiAccessPoints', ['signalStrength' => 8])
            ->setParamByKey('wifiAccessPoints', ['age' => 0])
            ->setParamByKey('wifiAccessPoints', ['channel' => 8])
            ->setParamByKey('wifiAccessPoints', ['signalToNoiseRatio' => -65])

            ->setParamByKey('wifiAccessPoints', ['macAddress' => '01:23:45:67:89:AC'])
            ->setParamByKey('wifiAccessPoints', ['signalStrength' => 4])
            ->setParamByKey('wifiAccessPoints', ['age' => 0])


            ->getResponseByKey('location');

        dd($d);
    }


    public function getSnapToRoads()
    {
        $d['a'] = \GoogleMaps::load('snapToRoads')
            ->setParamByKey('path', '-35.27801,149.12958|-35.28032,149.12907|-35.28099,149.12929|-35.28144,149.12984|-35.28194,149.13003|-35.28282,149.12956|-35.28302,149.12881|-35.28473,149.12836')
            ->get();

        $d['b'] = \GoogleMaps::load('snapToRoads')
            ->setParamByKey('path', ['60.170880,24.942795', '60.170879,24.942796', '60.170877,24.942796'])
            ->getResponseByKey('snappedPoints');

        dd($d);
    }


    public function getSpeedLimits()
    {
        $d['a'] =  \GoogleMaps::load('speedLimits')
            ->setParamByKey('path', '60.170880,24.942795|60.170879,24.942796|60.170877,24.942796')
            ->get();


        $d['b'] =  \GoogleMaps::load('speedLimits')
            ->setParamByKey('placeId', ['ChIJ1Wi6I2pNFmsRQL9GbW7qABM', 'ChIJ58xCoGlNFmsRUEZUbW7qABM', 'ChIJ9RhaiGlNFmsR0IxAbW7qABM'])
            ->getResponseByKey('snappedPoints');


        dd($d);
    }



    public function getTimeZone()
    {

        $d =   \GoogleMaps::load('timezone')
            ->setParam([
                'location' => '39.6034810,-119.6822510',
                'timestamp' => '1331161200'
            ])
            ->get();

        dd($d);
    }

    public function getNearbySearch()
    {

        $d =   \GoogleMaps::load('nearbysearch')

            ->setParam([
                'location'  => '-33.8670522,151.1957362',
                'radius'    => '500',
                'name'      => 'sydney',
                'type'      => 'airport',
            ])
            ->getResponseByKey('results.photos');

        dd($d);
    }


    public function getTextSearch()
    {

        $d =   \GoogleMaps::load('textsearch')
            ->setParam([
                'query'     => 'restaurants in Lytham St Annes',
                'radius'    => '500',
                'types'     => 'restaurant',
            ])
            ->getResponseByKey('results.formatted_address');

        dd($d);
    }

    public function getRadarSearch()
    {

        $d =   \GoogleMaps::load('radarsearch')
            ->setParam([
                'location'  => '51.503186,-0.126446',
                'radius'    => '500',
                'type'      => 'museum',
            ])
            ->getResponseByKey('results.place_id');

        dd($d);
    }

    public function getPlaceDetails()
    {

        $d =   \GoogleMaps::load('placedetails')
            ->setParamByKey('placeid', 'ChIJN1t_tDeuEmsRUsoyG83frY4')
            ->getResponseByKey('result.geometry.location');


        dd($d);
    }




    public function getPlacePhoto()
    {

        $d =  \GoogleMaps::load('placephoto')
            ->setParamByKey('maxwidth', '400')
            ->setParamByKey('photoreference', 'CnRtAAAATLZNl354RwP_9UKbQ_5Psy40texXePv4oAlgP4qNEkdIrkyse7rPXYGd9D_Uj1rVsQdWT4oRz4QrYAJNpFX7rzqqMlZw2h2E2y5IKMUZ7ouD_SlcHxYq1yL4KbKUv3qtWgTK0A6QbGh87GB3sscrHRIQiG2RrmU_jF4tENr9wGS_YxoUSSDrYjWmrNfeEHSGSc3FyhNLlBU')
            ->get();

        dd($d);
    }

    public function getPlaceAutoComplete()
    {

        $d =   \GoogleMaps::load('placeautocomplete')
            ->setParamByKey('input', 'Vict')
            ->setParamByKey('types', 'cities')
            ->setParamByKey('language', 'fr')
            ->getResponseByKey('predictions.place_id');


        dd($d);
    }

    public function getPlaceQueryAutoComplete()
    {

        $d =  \GoogleMaps::load('placequeryautocomplete')
            ->setParamByKey('input', 'Pizza near Lon')
            ->setParamByKey('language', 'gb')
            ->getResponseByKey('predictions.place_id');


        dd($d);
    }
}
