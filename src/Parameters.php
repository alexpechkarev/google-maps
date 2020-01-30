<?php namespace GoogleMaps;
/**
 * Description of Parameters
 *
 * @author Alexander Pechkarev <alexpechkarev@gmail.com>
 */

use URLify;

class Parameters{


    protected static $urlParam;

    /**
     * Generate Service URL parameters string
     * @param array $param
     * @return string
     */
    public static function getQueryString( &$param ){

        // Geocoding components parameter
        if( isset($param['components']) && is_array($param['components'])){
           $param['components'] = self::joinParam( $param['components'], ':', '|');
        }

        // Direction parameters
        if( isset($param['origin']) && is_array($param['origin'])){
           $param['origin'] = self::joinParam( $param['origin'], '', ':', false );
        }

        if( isset($param['destination']) && is_array($param['destination'])){
           $param['destination'] = self::joinParam( $param['destination'], '', ':', false );
        }

        if( isset($param['waypoints']) && is_array($param['waypoints'])){
           $param['waypoints'] = self::joinParam( $param['waypoints'], '', '|', false );
        }

        if( isset($param['avoid']) && is_array($param['avoid'])){
           $param['avoid'] = self::joinParam( $param['avoid'], '', '|', false );
        }

        // Distance Matrix parameters
        if( isset($param['origins']) && is_array($param['origins'])){
           $param['origins'] = self::joinParam( $param['origins'], '', '|', false );
        }

        if( isset($param['destinations']) && is_array($param['destinations'])){
           $param['destinations'] = self::joinParam( $param['destinations'], '', '|', false );
        }

        if( isset($param['transit_mode']) && is_array($param['transit_mode'])){
           $param['transit_mode'] = self::joinParam( $param['transit_mode'], '', '|', false );
        }

        // Elevation & Road parameters
        if( isset($param['locations']) && is_array($param['locations'])){
           $param['locations'] = self::joinParam( $param['locations'], '', '|', false );
        }
        if( isset($param['path']) && is_array($param['path'])){
           $param['path'] = self::joinParam( $param['path'], '', '|', false );
        }

        // Places & Time Zone parameters
        if( isset($param['location']) && is_array($param['location'])){
           $param['location'] = self::joinParam( $param['location'], '', ',', false );
        }

        /**
        * Place Search
        * Deprecation notices: Premium data (Zagat), types parameter, id and reference fields
        * Restricts the results to places matching the specified type.
        * Only one type may be specified (if more than one type is provided, all types following the first entry are ignored).
        */
        if( isset($param['types']) && is_array($param['types'])){
           #$param['types'] = self::joinParam( $param['types'], '', '|', false );
           $param['type'] = !empty( $param['types'][0] ) ? $param['types'][0] : "";
        }

        // reset to empty array
        self::$urlParam = [];

        return  self::joinParam( $param );

    }

    /**
     * Join array pairs into URL encoded string
     * @param array $param - single dimension array
     * @param string $join
     * @param string $glue
     * @param boolean $useKey
     * @return string
     */
    protected static function joinParam( $param = [], $join = '=', $glue = '&', $useKey = true){

        $allParam = [];

        foreach ($param as $key => $val)
        {
            if( is_array( $val ) ){
                if ($useKey && isset($val[0]) && is_array($val[0]) === false) {
                    $newValue = [];
                    foreach ($val as $element) {
                        $newValue[] = $key . $join . self::replaceCharacters($element);
                    }

                    return implode($glue, $newValue);
                } else {
                    $val = self::joinParam( $val, $join, $glue, $useKey);
                }
            }

            // omit parameters with empty values
            if( !empty( $val )){
                #self::$urlParam[] = $useKey
                $allParam[] = $useKey
                        ? $key . $join .urlencode(URLify::downcode($val))
                        : $join .urlencode(URLify::downcode($val));
            }
        }

        // no parameters given
        if( is_null( $allParam ) ) {
            return '';
        }

        $allParam = self::replaceCharacters($allParam);

        return implode($glue, $allParam);
    }

    /**
     * Replace special characters
     * @param array $allParam
     * @return mixed
     */
    private static function replaceCharacters($allParam)
    {
        // replace special characters
        $allParam = str_replace(['%2C'], [','], $allParam);
        $allParam = str_replace(['%252C'], [','], $allParam);
        $allParam = str_replace(['%3A'], [':'], $allParam);
        return str_replace(['%7C'], ['|'], $allParam);
    }

    public static function resetParams()
    {
        self::$urlParam = [];
    }
}
