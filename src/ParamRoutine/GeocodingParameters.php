<?php namespace App\GoogleMaps\ParamRoutine;
/**
 * Description of GeocodingParameters
 *
 * @author alex.petchkarev
 */
use App\GoogleMaps\ParamRoutine\ParamRoutineInterface;
use App\GoogleMaps\GoogleMaps;

class GeocodingParameters implements ParamRoutineInterface{
      
    
    /**
     * Generate Service URL parameters string
     * @param array $param
     * @return string
     */
    public function getQueryString( &$param ){

        
        if( is_array($param['components'])){
           $param['components'] = Googlemaps::joinParam( $param['components'], '=', '&');
        }         
        
        $rsp = GoogleMaps::joinParam( $param );
              
        
        return $rsp;
    }
    
    
   
    
}

?>
