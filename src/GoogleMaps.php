<?php namespace GoogleMaps;

/**
 * Description of GoogleMaps
 *
 * @author Alexander Pechkarev <alexpechkarev@gmail.com>
 */

class GoogleMaps extends \GoogleMaps\WebService{
    
    /**
     * Array of classes to handle web service request
     * By default WebService class will be used
     * @var service => class to use
     */
    protected $webServices = [
        'directions' => '\GoogleMaps\Directions'
    ];
    
      
      /**
       * Bootstraping Web Service
       * @param string $service
       * @return GooglMaps\WebService
       */
      public function load( $service ){
          
             // is overwrite class specified 
            $class = array_key_exists($service, $this->webServices)
                    ? new $this->webServices[$service]
                    : $this;
            
            $class->build( $service );
            
            return $class;
      }
      
      
      protected function build($service) {
          parent::build($service);
      }
      
      /***/
      

      
}
