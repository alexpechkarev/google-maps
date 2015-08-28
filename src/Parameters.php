<?php namespace App\GoogleMaps;
/**
 * Description of Parameters
 *
 * @author alex.petchkarev
 */
use App\GoogleMaps\GoogleMaps;

class Parameters{
      
    
    /**
     * Generate Service URL parameters string
     * @param array $param
     * @return string
     */
    public static function getQueryString( &$param ){
        
        // Geocoding components parameter
        if( isset($param['components']) && is_array($param['components'])){
           $param['components'] = GoogleMaps::joinParam( $param['components'], '=', '&');
        }   
        ///
        
        // Direction parameters
        if( isset($param['origin']) && is_array($param['origin'])){
           $param['origin'] = GoogleMaps::joinParam( $param['origin'], '', ':', false );
        }   
        
        if( isset($param['destination']) && is_array($param['destination'])){
           $param['destination'] = GoogleMaps::joinParam( $param['destination'], '', ':', false );
        }      
        
        if( isset($param['waypoints']) && is_array($param['waypoints'])){
           $param['waypoints'] = GoogleMaps::joinParam( $param['waypoints'], '', '|', false );
        } 
        
        if( isset($param['avoid']) && is_array($param['avoid'])){
           $param['avoid'] = GoogleMaps::joinParam( $param['avoid'], '', '|', false );
        }     
        ///
        
        // Distance Matrix parameters
        if( isset($param['origins']) && is_array($param['origins'])){
           $param['origins'] = GoogleMaps::joinParam( $param['origins'], '', '|', false );
        }   
        
        if( isset($param['destinations']) && is_array($param['destinations'])){
           $param['destinations'] = GoogleMaps::joinParam( $param['destinations'], '', '|', false );
        }   
        
        if( isset($param['transit_mode']) && is_array($param['transit_mode'])){
           $param['transit_mode'] = GoogleMaps::joinParam( $param['transit_mode'], '', '|', false );
        } 
        ///
        
        // Elevation & Road parameters
        if( isset($param['locations']) && is_array($param['locations'])){
           $param['locations'] = GoogleMaps::joinParam( $param['locations'], '', '|', false );
        }   
        if( isset($param['path']) && is_array($param['path'])){
           $param['path'] = GoogleMaps::joinParam( $param['path'], '', '|', false );
        } 
        ///
        
        // Places & Time Zone parameters
        if( isset($param['location']) && is_array($param['location'])){
           $param['location'] = GoogleMaps::joinParam( $param['location'], '', ',', false );
        } 
        
        if( isset($param['types']) && is_array($param['types'])){
           $param['types'] = GoogleMaps::joinParam( $param['types'], '', '|', false );
        }        
        ///
                
                
   // Geolocate parameters
        if( isset($param['location']) && is_array($param['location'])){
           $param['location'] = GoogleMaps::joinParam( $param['location'], '', ',', false );
        } 
        
        if( isset($param['types']) && is_array($param['types'])){
           $param['types'] = GoogleMaps::joinParam( $param['types'], '', '|', false );
        }        
        ///        
                
        
        // Roads parameters
        if( isset($param['placeId']) && is_array($param['placeId'])){
            $tmp = '';
            foreach( $param['placeId'] as $key => $val ){
                $tmp.= $key == 0 ? $val : '&placeId='.$val;
            }
            
            $param['placeId'] = $tmp;
            unset($tmp, $key, $val);
        } 
        ///                         
        

        return  GoogleMaps::joinParam( $param );

    }
    
    
   
    
}

?>
