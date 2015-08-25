<?php namespace App\GoogleMaps\ParamRoutine;
/**
 * Description of DistanceMatrix
 *
 * @author alex.petchkarev
 */
use App\GoogleMaps\ParamRoutine\ParamRoutineInterface;
use App\GoogleMaps\GoogleMaps;

class DistanceMatrixParameters implements ParamRoutineInterface{
      
    
    /**
     * Generate Service URL parameters string
     * @param array $param
     * @return string
     */
    public function getQueryString( &$param ){
        
        if( is_array($param['origins'])){
           $param['origins'] = Googlemaps::joinParam( $param['origins'], '', '|', false );
        }   
        
        if( is_array($param['destinations'])){
           $param['destinations'] = Googlemaps::joinParam( $param['destinations'], '', '|', false );
        }   
        
        
        if( is_array($param['transit_mode'])){
           $param['transit_mode'] = Googlemaps::joinParam( $param['transit_mode'], '', '|', false );
        }         
        return GoogleMaps::joinParam( $param );

    }
    
    
   
    
}

?>
