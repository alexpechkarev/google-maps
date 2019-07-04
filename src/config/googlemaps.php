<?php

return [


    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Will be used for all web services,
    | unless overwritten bellow using 'key' parameter
    |
    |
    */

    'key'       => 'ADD_YOUR_SERVICE_KEY_HERE',

    /*
    |--------------------------------------------------------------------------
    | Verify SSL Peer
    |--------------------------------------------------------------------------
    |
    | Will be used for all web services to verify
    | SSL peer (SSL certificate validation)
    |
     */
    'ssl_verify_peer' => FALSE,

    /*
    |--------------------------------------------------------------------------
    | Service URL
    |--------------------------------------------------------------------------
    | url - web service URL
    | type - request type POST or GET
    | key - API key, if different to API key above
    | endpoint - boolean, indicates whenever output parameter to be used in the request or not
    | responseDefaultKey - specify default field value to be returned when calling getByKey()
    | param - accepted request parameters
    |
    */

    'service' => [

        'geocoding' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/geocode/',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    => 'place_id',
                        'param'                 => [
                                                    'address'       => null,
                                                    'bounds'        => null,
                                                    'key'           => null,
                                                    'region'        => null,
                                                    'language'      => null,
                                                    'result_type'   => null,
                                                    'location_type' => null,
                                                    'latlng'        => null,
                                                    'place_id'      => null,
                                                    'components'    => [
                                                        'route'                 => null,
                                                        'locality'              => null,
                                                        'administrative_area'   => null,
                                                        'postal_code'           => null,
                                                        'country'               => null,
                                                        ]
                                                    ]
        ],



        'directions' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/directions/',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    =>  'geocoded_waypoints',
                        'decodePolyline'        =>  true, // true = decode overview_polyline.points to an array of points
                        'param'                 => [
                                                    'origin'          => null, // required
                                                    'destination'     => null, //required
                                                    'mode'            => null,
                                                    'waypoints'       => null,
                                                    'place_id'        => null,
                                                    'alternatives'    => null,
                                                    'avoid'           => null,
                                                    'language'        => null,
                                                    'units'           => null,
                                                    'region'          => null,
                                                    'departure_time'  => null,
                                                    'arrival_time'    => null,
                                                    'transit_mode'    => null,
                                                    'transit_routing_preference' => null,
                                                    ]
        ],


        'distancematrix' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/distancematrix/',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    => 'origin_addresses',
                        'param'                 => [
                                                    'origins'        => null,
                                                    'destinations'   => null,
                                                    'key'            => null,
                                                    'mode'           => null,
                                                    'language'       => null,
                                                    'avoid'          => null,
                                                    'units'          => null,
                                                    'departure_time' => null,
                                                    'arrival_time'   => null,
                                                    'transit_mode'   => null,
                                                    'transit_routing_preference' => null,

                                                    ]
        ],


        'elevation' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/elevation/',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    => 'elevation',
                        'param'                 => [
                                                    'locations'     => null,
                                                    'path'          => null,
                                                    'samples'       => null,
                                                    'key'           => null,
                                                    ]
        ],


        'geolocate' => [
                        'url'                   => 'https://www.googleapis.com/geolocation/v1/geolocate?',
                        'type'                  => 'POST',
                        'key'                   =>  null,
                        'endpoint'              =>  false,
                        'responseDefaultKey'    => 'location',
                        'param'                 => [
                                                    'homeMobileCountryCode' => null,
                                                    'homeMobileNetworkCode' => null,
                                                    'radioType'             => null,
                                                    'carrier'               => null,
                                                    'considerIp'            => null,
                                                    'cellTowers' => [
                                                        'cellId'            => null,
                                                        'locationAreaCode'  => null,
                                                        'mobileCountryCode' => null,
                                                        'mobileNetworkCode' => null,
                                                        'age'               => null,
                                                        'signalStrength'    => null,
                                                        'timingAdvance'     => null,
                                                        ],
                                                    'wifiAccessPoints' => [
                                                        'macAddress'        => null,
                                                        'signalStrength'    => null,
                                                        'age'               => null,
                                                        'channel'           => null,
                                                        'signalToNoiseRatio'=> null,
                                                        ],
                                                    ]
        ],



        'snapToRoads' => [
                        'url'                   => 'https://roads.googleapis.com/v1/snapToRoads?',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  false,
                        'responseDefaultKey'    => 'snappedPoints',
                        'param'                 => [
                                                    'locations'     => null,
                                                    'path'          => null,
                                                    'samples'       => null,
                                                    'key'           => null,
                                                    ]
        ],


        'speedLimits' => [
                        'url'                   => 'https://roads.googleapis.com/v1/speedLimits?',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  false,
                        'responseDefaultKey'    => 'speedLimits',
                        'param'                 => [
                                                    'path'          => null,
                                                    'placeId'       => null,
                                                    'units'         => null,
                                                    'key'           => null,
                                                    ]
        ],


        'timezone' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/timezone/',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    => 'dstOffset',
                        'param'                 => [
                                                    'location'  => null,
                                                    'timestamp' => null,
                                                    'key'       => null,
                                                    'language'  => null,

                                                    ]
        ],



        'nearbysearch' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/place/nearbysearch/',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    => 'results',
                        'param'                 => [
                                                    'key'           => null,
                                                    'location'      => null,
                                                    'radius'        => null,
                                                    'keyword'       => null,
                                                    'language'      => null,
                                                    'minprice'      => null,
                                                    'maxprice'      => null,
                                                    'name'          => null,
                                                    'opennow'       => null,
                                                    'rankby'        => null,
                                                    'type'          => null, // types depricated, one type may be specified
                                                    'pagetoken'     => null,
                                                    'zagatselected' => null,
                                                    ]
        ],



        'textsearch' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/place/textsearch/',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    => 'results',
                        'param'                 => [
                                                    'key'           => null,
                                                    'query'         => null,
                                                    'location'      => null,
                                                    'radius'        => null,
                                                    'language'      => null,
                                                    'minprice'      => null,
                                                    'maxprice'      => null,
                                                    'opennow'       => null,
                                                    'type'          => null, // types deprecated, one type may be specified
                                                    'pagetoken'     => null,
                                                    'zagatselected' => null,
                                                   ]
        ],



        'radarsearch' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/place/radarsearch/',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    => 'geometry',
                        'param'                 => [
                                                    'key'           => null,
                                                    'radius'        => null,
                                                    'location'      => null,
                                                    'keyword'       => null,
                                                    'minprice'      => null,
                                                    'maxprice'      => null,
                                                    'opennow'       => null,
                                                    'name'          => null,
                                                    'type'          => null, // types depricated, one type may be specified
                                                    'zagatselected' => null,
                                                    ]
        ],



        'placedetails' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/place/details/',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    => 'result',
                        'param'                 => [
                                                    'key'           => null,
                                                    'placeid'       => null,
                                                    'extensions'    => null,
                                                    'language'      => null,
                                                    ]
        ],


        'placeadd' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/place/add/',
                        'type'                  => 'POST',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    => 'place_id',
                        'param'                 => [
                                                    'key'           => null,
                                                    'accuracy'      => null,
                                                    'address'       => null,
                                                    'language'      => null,
                                                    'location'      => null,
                                                    'name'          => null,
                                                    'phone_number'  => null,
                                                    'types'         => null,// according to docs types still required as string parameter
                                                    'type'          => null, // types deprecated, one type may be specified
                                                    'website'       => null,
                                                    ]
        ],


        'placedelete' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/place/delete/',
                        'type'                  => 'POST',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    => 'status',
                        'param'                 => [
                                                    'key'           => null,
                                                    'place_id'      => null,

                                                    ]
        ],




        'placephoto' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/place/photo?',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  false,
                        'responseDefaultKey'    => 'image',
                        'param'                 => [
                                                    'key'           => null,
                                                    'photoreference'=> null,
                                                    'maxheight'     => null,
                                                    'maxwidth'      => null,
                                                    ]
        ],





        'placeautocomplete' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/place/autocomplete/',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    => 'predictions',
                        'param'                 => [
                                                    'key'           => null,
                                                    'input'         => null,
                                                    'offset'        => null,
                                                    'location'      => null,
                                                    'radius'        => null,
                                                    'language'      => null,
                                                    'types'         => null, // use string as parameter
                                                    'type'          => null, // types deprecated, one type may be specified
                                                    'components'    => null,
                                                    ]
        ],



        'placequeryautocomplete' => [
                        'url'                   => 'https://maps.googleapis.com/maps/api/place/queryautocomplete/',
                        'type'                  => 'GET',
                        'key'                   =>  null,
                        'endpoint'              =>  true,
                        'responseDefaultKey'    => 'predictions',
                        'param'                 => [
                                                    'key'           => null,
                                                    'input'         => null,
                                                    'offset'        => null,
                                                    'location'      => null,
                                                    'radius'        => null,
                                                    'language'      => null,
                                                    ]
        ],

    ],




    /*
    |--------------------------------------------------------------------------
    | End point
    |--------------------------------------------------------------------------
    |
    |
    */

    'endpoint' => [
        'xml'           => 'xml?',
        'json'          => 'json?',
    ],



];
