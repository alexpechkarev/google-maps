<?php namespace GoogleMaps;

use GeometryLibrary\PolyUtil;
use Illuminate\Support\Arr;

/**
 * Description of GoogleMaps
 *
 * @author Alexander Pechkarev <alexpechkarev@gmail.com>
 */
class Directions extends WebService{
    /**
     * Get Web Service Response
     *
     * @param string|false $needle
     * @return string
     * @throws \ErrorException
     */
    public function get( $needle = false ){

        if( $this->service['decodePolyline'] ){
            $this->setEndpoint('json');

            return $this->decode( parent::get( $needle ));
        }

        return parent::get( $needle );
    }
    /***/

    /**
     * To determine whether a point falls on or near a polyline, or on or near
     * the edge of a polygon, pass the point, the polyline/polygon, and
     * optionally a tolerance value in degrees
     * https://developers.google.com/maps/documentation/javascript/geometry#isLocationOnEdge
     *
     * @param double $lat
     * @param double $lng
     * @param double $tolerance
     * @return boolean
     * @throws \ErrorException
     */
    public function isLocationOnEdge( $lat, $lng, $tolerance = 0.1){

        $point = [
            'lat' => $lat,
            'lng' => $lng
        ];

        $polygon = Arr::get( json_decode( $this->get(), true ), 'routes.0.overview_polyline.points') ;

        return PolyUtil::isLocationOnEdge($point, $polygon, $tolerance);
    }

    /**
     * To find whether a given point falls within a polygon
     * https://developers.google.com/maps/documentation/javascript/geometry#containsLocation
     *
     * @param double $lat
     * @param double $lng
     * @return boolean
     * @throws \ErrorException
     */
    public function containsLocation($lat, $lng){

        $point = [
            'lat' => $lat,
            'lng' => $lng
        ];

        $polygon = Arr::get( json_decode( $this->get(), true ), 'routes.0.overview_polyline.points') ;

        return PolyUtil::containsLocation($point, $polygon);
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
    */

   /**
    * Get web service polyline parameter being decoded
    * @param $rsp - string
    * @param string $param - response key
    * @return string - JSON
    */
    protected function decode( $rsp, $param = 'routes.overview_polyline.points' ){
        $needle = metaphone($param);

        // get response
        $obj = json_decode( $rsp, true);

        // flatten array into single level array using 'dot' notation
        $obj_dot = Arr::dot($obj);
        // create empty response
        $response = [];
        // iterate
        foreach( $obj_dot as $key => $val){

            // Calculate the metaphone key and compare with needle
            $val =  strcmp( metaphone($key, strlen($needle)), $needle) === 0
                    ? PolyUtil::decode($val) // if matched decode polyline
                    : $val;

                Arr::set($response, $key, $val);
        }

        return json_encode($response, JSON_PRETTY_PRINT) ;
    }
}
