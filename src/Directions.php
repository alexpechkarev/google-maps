<?php namespace GoogleMaps;

/**
 * Description of GoogleMaps
 *
 * @author Alexander Pechkarev <alexpechkarev@gmail.com>
 */

use \GoogleMaps\Parameters;
use GeometryLibrary\PolyUtil;

class Directions extends \GoogleMaps\WebService{
    
     
    
    
    /**
     * Get Web Service Response
     * @return type
     */
    public function get(){
        
        // use output parameter if required by the service
        $this->requestUrl.= $this->service['endpoint']
                            ? $this->endpoint
                            : ''; 
        
        // set API Key
        $this->requestUrl.= 'key='.urlencode( $this->key );
        
        // build query string
        $this->requestUrl.='&'. Parameters::getQueryString( $this->service['param'] ); 

        
        
        return $this->service['decodePolyline']
                    ? $this->decode( $this->make() )
                    : $this->make();         
        
        
    }
    /***/
    
    /**
     * To determine whether a point falls on or near a polyline, or on or near 
     * the edge of a polygon, pass the point, the polyline/polygon, and 
     * optionally a tolerance value in degrees
     * https://developers.google.com/maps/documentation/javascript/geometry#isLocationOnEdge
     * @param double $lat
     * @param double $lng
     * @param double $tolrance
     * @return boolean
     */
    public function isLocationOnEdge( $lat, $lng, $tolrance = 0.1){
        
        $point = [
            'lat' => $lat,
            'lng' => $lng
        ];
        
        $polygon = array_get( json_decode( $this->get(), true ), 'routes.0.overview_polyline.points') ;
        
        return PolyUtil::isLocationOnEdge($point, $polygon, $tolrance);
       
    }
    /***/
    
    /**
     * To find whether a given point falls within a polygon
     * https://developers.google.com/maps/documentation/javascript/geometry#containsLocation
     * @param double $lat
     * @param double $lng
     * @return boolean
     */
    public function containsLocation($lat, $lng){
        
        $point = [
            'lat' => $lat,
            'lng' => $lng
        ];  
        
        $polygon = array_get( json_decode( $this->get(), true ), 'routes.0.overview_polyline.points') ;      
        
        return PolyUtil::containsLocation($point, $polygon);        
    }
    /***/
    
    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
    */    
    
   /**
    * Get web wervice polyline parameter being decoded
    * @param $rsp - string 
    * @param string $param - response key
    * @return string - JSON
    */ 
   protected function decode( $rsp, $param = 'routes.overview_polyline.points' ){
       
       $needle = metaphone($param);
       
        // get response
        $obj = json_decode( $rsp, true);       
                        
        // flatten array into single level array using 'dot' notation
        $obj_dot = array_dot($obj);
        // create empty response
        $response = [];
        // iterate 
        foreach( $obj_dot as $key => $val){
            
            // Calculate the metaphone key and compare with needle
            $val =  strcmp( metaphone($key, strlen($needle)), $needle) === 0 
                    ? PolyUtil::decode($val) // if matched decode polyline
                    : $val;
            
                array_set($response, $key, $val);
        }        
        

        return json_encode($response, JSON_PRETTY_PRINT) ;

        
        
    }
    /***/     
       
      
}
