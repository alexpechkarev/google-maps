<?php namespace App\GoogleMaps\ParamRoutine;
/**
 * Description of DirectionsParameters
 *
 * @author alex.petchkarev
 */
use App\GoogleMaps\ParamRoutine\ParamRoutineInterface;
use App\GoogleMaps\GoogleMaps;

class DirectionsParameters implements ParamRoutineInterface{
      
    
    /**
     * Generate Service URL parameters string
     * @param array $param
     * @return string
     */
    public function getQueryString( &$param ){
  
        
        if( is_array($param['origin'])){
           $param['origin'] = Googlemaps::joinParam( $param['origin'], '', ':', false );
        }   
        
        if( is_array($param['destination'])){
           $param['destination'] = Googlemaps::joinParam( $param['destination'], '', ':', false );
        }      
        
        if( is_array($param['waypoints'])){
           $param['waypoints'] = Googlemaps::joinParam( $param['waypoints'], '', '|', false );
        } 
        
        if( is_array($param['avoid'])){
           $param['avoid'] = Googlemaps::joinParam( $param['avoid'], '', '|', false );
        }          
        
        return GoogleMaps::joinParam( $param );
      
    }
    
    
   
    
}

?>
