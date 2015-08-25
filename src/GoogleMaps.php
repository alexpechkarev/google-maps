<?php namespace App\GoogleMaps;

/**
 * Description of GoogleMaps
 *
 * @author Alexander Pechkarev <alexpechkarev@gmail.com>
 */

use \App\GoogleMaps\WebService;

class GoogleMaps{
    
    
    /*
    |--------------------------------------------------------------------------
    | Request
    |--------------------------------------------------------------------------
    | Request URL with encoded parameters
    |
    */     
    private $requestUrl; 
    
    
    /*
    |--------------------------------------------------------------------------
    | Default Endpoint
    |--------------------------------------------------------------------------
    |
    */     
    private $endpoint;      
    
    
    /*
    |--------------------------------------------------------------------------
    | Parameters
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    private $param;
    
    
    
    /*
    |--------------------------------------------------------------------------
    | Web Service
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    private $service;    
    
    /*
    |--------------------------------------------------------------------------
    | Web Service Parameter Routine
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    private $paramroutine =  [
        'geocoding'                 => 'App\GoogleMaps\ParamRoutine\GeocodingParameters',
        'directions'                => 'App\GoogleMaps\ParamRoutine\DirectionsParameters',
        'distancematrix'            => 'App\GoogleMaps\ParamRoutine\DistanceMatrixParameters',
        'elevation'                 => 'App\GoogleMaps\ParamRoutine\ElevationParameters',
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
        ];    
    
    /*
    |--------------------------------------------------------------------------
    | Response Default Keys
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    private $defaultKey =  [
        'geocoding'                 => 'place_id',
        'directions'                => 'geocoded_waypoints',
        'distancematrix'            => 'App\GoogleMaps\ParamRoutine\DistanceMatrixParameters',
        'elevation'                 => 'App\GoogleMaps\ParamRoutine\ElevationParameters',
        'geolocate'                 => '',
        'roads'                     => '',
        'speedLimits'               => '',
        'timezone'                  => '',
        'nearbysearch'              => '',
        'textsearch'                => '',
        'radarsearch'               => '',
        'placedetails'              => '',
        'placeadd'                  => '',
        'placedelete'               => '',
        'placephoto'                => '',
        'placeautocomplete'         => '',
        'placequeryautocomplete'    => '',
        ];      
        
    
    /**
     * Class constructor
     */
    public function __construct()
      { 
      }
      /***/    
    
      
      /**
       * Bootstraping Web Service
       * @param string $service
       * @return GooglMaps\WebService
       */
      public function load( $service ){
          
            $this->service = $service;
            $this->build();

            return new WebService( $this->defaultKey[ $service ] );          
      }
      /***/
      
          
      
    /**
     * Setting endpoint
     * @param string $key
     */
    public function setEndpoint( $key = 'json' ){
                     
        $this->endpoint = array_get(config('googlemaps.endpoint'), $key, 'json?');
    }
    /***/  
    
    /**
     * Getting endpoint
     * @return string
     */
    public function getEndpoint( ){
        
        return $this->endpoint;
    }
    /***/      
      
    
    /**
     * Set parameter by key
     * @param type $key
     * @param type $value
     */
    public function setParamByKey($key, $value){
        
         if( array_key_exists( $key, array_dot( $this->param ) ) ){
             
             array_set($this->param, $key, $value);
         }   
    }
    /***/
   
    
    /**
     * Get parameter by the key
     * @param string $key
     * @return mixed
     */ 
    public function getParamByKey($key){
        
         if( array_key_exists( $key, array_dot( $this->param ) ) ){
             
             return array_get($this->param, $key);
         }
    }
    /***/    
      
    /**
     * Return parameters array
     * @return array
     */
    public function getParam(){
        return $this->param;
    }
    /***/
    
    /**
     * Perform Request to Web Service
     * @return object
     */
    public function get(){
       
        $this->requestUrl.= $this->endpoint
                .app( $this->paramroutine[ $this->service ] )->getQueryString( $this->param );
        
        return $this->make();        
        
    }
    /***/
    
    
    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
    */     
    
    /**
     * Setup service parameters
     */
    protected function build(){
        
            $this->validateConfig();

            // set default endpoint
            $this->setEndpoint();
            
            // setting service key
            $this->param = config('googlemaps.param.'.$this->service);
                                                              

            // setting service key
            $this->param['key'] = is_array( config('googlemaps.key') )
                                                  ? config('googlemaps.key.'.$this->service)
                                                  : config('googlemaps.key'); 
            
            // set request URL
            $this->requestUrl = config('googlemaps.url.'.$this->service);         
    }
    /***/
    
    
    /**
     * Validate configuration file
     * @throws \ErrorException
     */ 
    protected function validateConfig(){
        
            // Check for config file
            if( !\Config::has('googlemaps')){
                
                throw new \ErrorException('Unable to find config file.');
            }        
            
            // Validate Key parameter
            if(!array_key_exists('key', config('googlemaps') ) ){
                
                throw new \ErrorException('Unable to find Key parameter in configuration file.');
            }

            if( is_array(config('googlemaps.key') )
                    && ( !array_key_exists($this->service, config('googlemaps.key')) 
                    || config('googlemaps.key.'.$this->service) === "" )){
                
                throw new \ErrorException('Service Key must not be empty.');
            }            
            
            
            
            if( !is_array(config('googlemaps.key'))
                    && config('googlemaps.key') === "" ){
                
                throw new \ErrorException('Service Key must not be empty.');
            }             
            
            
            // Validate Key parameter
            if(!array_key_exists('url', config('googlemaps') )
                    && !array_key_exists($this->service, config('googlemaps.url') )
                    || !count( config('googlemaps.url') )
                    || !count( config('googlemaps.url.'.$this->service)  )
                    ){
                throw new \ErrorException('Web service URL must not be empty.');
            }            
                        
            
            // Validate Endpoint
            if(!array_key_exists('endpoint', config('googlemaps') )
                    || !count(config('googlemaps.endpoint') < 1)){
                throw new \ErrorException('End point must not be empty.');
            }  
             
            
            // Validate Service Parameters
            if(!array_key_exists('param', config('googlemaps') )
                    || !array_key_exists( $this->service, config('googlemaps.param') )
                    || !count(config('googlemaps.param.'.$this->service) < 1)){
                throw new \ErrorException('Service parameters must be defined in config file.');
            }             
                     
                    
    }
    /***/     
    
    
    
    
    /**
     * Join array pairs into URL encoded string
     * @param array $param - single dimension array
     * @param string $join
     * @param string $glue
     * @param boolean $useKey
     * @return string
     */
    public static function joinParam( $param = [], $join = '=', $glue = '&', $useKey = true){
        
        $request = [];
        foreach($param as $key => $val)
        {  
                // ommit parameters with empty values
                if( !empty( $val )){
                    $request[] = $useKey
                                ? $key . $join .urlencode($val)
                                #: $join .urlencode($val);
                                : $join .$val;
                }
        } 

        return implode($glue, $request);        
    }
    /***/
    

    
    /**
     * Make cURL request to given URL
     * @return object
     */
    protected function make(){
      
       $ch = curl_init( $this->requestUrl );
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

       $output = curl_exec($ch);
       
      if( $output === false ){
          throw new \ErrorException( curl_error($ch) );
      }


      curl_close($ch);
      return $output;

    }
    /***/        
      
           
}
