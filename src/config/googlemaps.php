<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Service Keys
    |--------------------------------------------------------------------------
    |
    | Service key - required
    |
    | Different service key can be supplied
    |
    |    'key'       => [
    |               'geocoding'  => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
    |               'directions' => 'BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB',
    |        ],
    |
    */
    
    'key'       => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
        

    
    /*
    |--------------------------------------------------------------------------
    | Service URL
    |--------------------------------------------------------------------------
    |
    |
    */

    'url' => [
        'geocoding'                 => 'https://maps.googleapis.com/maps/api/geocode/',
        'directions'                => 'https://maps.googleapis.com/maps/api/directions/',
        'distancematrix'            => 'https://maps.googleapis.com/maps/api/distancematrix/',
        'elevation'                 => 'https://maps.googleapis.com/maps/api/elevation/',
        'geolocate'                 => 'https://www.googleapis.com/geolocation/v1/geolocate/',
        'roads'                     => 'https://roads.googleapis.com/v1/snapToRoads?',
        'speedLimits'               => 'https://roads.googleapis.com/v1/speedLimits?',
        'timezone'                  => 'https://maps.googleapis.com/maps/api/timezone/',
        'nearbysearch'              => 'https://maps.googleapis.com/maps/api/place/nearbysearch/',
        'textsearch'                => 'https://maps.googleapis.com/maps/api/place/textsearch/',
        'radarsearch'               => 'https://maps.googleapis.com/maps/api/place/radarsearch/',
        'placedetails'              => 'https://maps.googleapis.com/maps/api/place/details/',
        'placeadd'                  => 'https://maps.googleapis.com/maps/api/place/add/',
        'placedelete'               => 'https://maps.googleapis.com/maps/api/place/delete/',
        'placephoto'                => 'https://maps.googleapis.com/maps/api/place/photo?',
        'placeautocomplete'         => 'https://maps.googleapis.com/maps/api/place/autocomplete/',
        'placequeryautocomplete'    => 'https://maps.googleapis.com/maps/api/place/queryautocomplete/',
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
    
    
    /*
    |--------------------------------------------------------------------------
    | Service parameters
    |--------------------------------------------------------------------------
    |
    |
    */

    'param' => [
        'geocoding' => [
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
          ],
        
        'directions' => [
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
        ],
        
        'distancematrix'    => [
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

        ], 
        
        'elevation' => [
            'locations'     => null,
            'path'          => null,
            'samples'       => null,
            'key'           => null,
        ],   

        'geolocate'   => [
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


        ],  

        'roads' =>[
            'locations'     => null,
            'path'          => null,
            'samples'       => null,
            'key'           => null,
        ],

        'speedLimits' =>[
            'path'          => null,
            'placeId'       => null,
            'units'         => null,
            'key'           => null,
        ], 
        
        'timezone' => [
            'location'  => null,
            'timestamp' => null,
            'key'       => null,
            'language'  => null,
            
        ],
        
        'nearbysearch' => [
            'key' => null,  
            'location' => null,
            'radius' => null,
            'keyword' => null,
            'language' => null,
            'minprice' => null,
            'maxprice' => null,
            'name' => null,
            'opennow' => null,
            'rankby' => null,
            'types' => null,
            'pagetoken' => null,
            'zagatselected' => null,
        ],
        
        'textsearch' => [
            'key' => null,  
            'query' => null,
            'location' => null,
            'radius' => null,
            'language' => null,
            'minprice' => null,
            'maxprice' => null,
            'opennow' => null,
            'types' => null,
            'pagetoken' => null,
            'zagatselected' => null,
        ], 
        
        'radarsearch' => [
            'key' => null,  
            'radius' => null,
            'location' => null,
            'keyword' => null,
            'minprice' => null,
            'maxprice' => null,
            'opennow' => null,
            'name' => null,
            'types' => null,
            'zagatselected' => null,
        ],        
        
        
        'placedetails' => [
            'key' => null,  
            'placeid' => null,
            'extensions' => null,
            'language' => null,
        ], 
        
        
        'placeadd' => [
            'key' => null,  
            'accuracy' => null,
            'address' => null,
            'language' => null,
            'location' => null,
            'name' => null,
            'phone_number' => null,
            'types' => null,
            'website' => null,
            'name' => null,
            'name' => null,
            
        ],         
        
        
        'placedelete' => [
            'key' => null,  
            'place_id' => null,
            
        ],  
        
        'placephoto' => [
            'key' => null,  
            'photoreference' => null,
            'maxheight' => null,
            'maxwidth' => null,
            
        ],    
        
        
        'placeautocomplete' => [
            'key' => null,  
            'input' => null,
            'offset' => null,
            'location' => null,
            'radius' => null,
            'language' => null,
            'types' => null,
            'components' => null,
        ],         
        
        'placequeryautocomplete' => [
            'key' => null,  
            'input' => null,
            'offset' => null,
            'location' => null,
            'radius' => null,
            'language' => null,
        ],        
    ],    
	
        
        
	

];
