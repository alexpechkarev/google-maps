<?php namespace App\GoogleMaps\ParamRoutine;
/**
 * Description of DistanceMatrix
 *
 * @author alex.petchkarev
 */
use App\GoogleMaps\ParamRoutine\ParamRoutineInterface;
use App\GoogleMaps\GoogleMaps;

class ElevationParameters implements ParamRoutineInterface{
      
    
    /**
     * Generate Service URL parameters string
     * @param array $param
     * @return string
     */
    public function getQueryString( &$param ){
        
        if( is_array($param['locations'])){
           $param['locations'] = Googlemaps::joinParam( $param['locations'], '', '|', false );
        }   
        if( is_array($param['path'])){
           $param['path'] = Googlemaps::joinParam( $param['path'], '', '|', false );
        }   
              
        return GoogleMaps::joinParam( $param );

    }
    
    
   
    
}

?>
