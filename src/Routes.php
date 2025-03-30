<?php

namespace GoogleMaps;

use ErrorException;
use GeometryLibrary\PolyUtil;

/**
 * Routes of GoogleMaps
 *
 * @author Alexander Pechkarev <alexpechkarev@gmail.com>
 *
 */
class Routes extends WebService
{

    /**
     * A response field mask
     * @var string
     */
    protected $fieldMask = '*';
    /**
     * Merge request parameters
     *
     * @param string|false $needle
     * @return void
     * @throws \ErrorException
     */
    public function setParam($param)
    {
        // merge with existing parameters
        try {
            $this->service['param'] = array_merge($this->service['param'], $param);
        } catch (ErrorException $e) {
            throw new ErrorException('Invalid parameter: ' . $e->getMessage());
        }
        // Check if the parameter is an array
        if (!is_array($param)) {
            throw new ErrorException('Invalid parameter: ' . $param);
        }



        // Fileter out null values from the array
        $this->service['param'] = array_filter($this->service['param'], function ($value) {
            return $value !== null;
        });
        return $this;
    }


    /**
     * Set field mask
     *
     * @param string $fieldMask
     * @return void
     * @throws \ErrorException
     */
    public function setFieldMask($fieldMask)
    {
        try {
            $this->fieldMask = strval($fieldMask);
        } catch (ErrorException $e) {
            throw new ErrorException('Error setting field mask ' . $e->getMessage());
        }
        return $this;
    }



    /**
     * Make cURL request to given URL
     * @param boolean $isPost
     * @return bool|object
     * @throws \ErrorException
     */
    public function fetch()
    {

        $ch = curl_init($this->requestUrl);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Goog-Api-Key: ' . $this->key,
            'X-Goog-FieldMask: ' . $this->fieldMask,
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,   json_encode($this->service['param']));


        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,  $this->connectionTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT,  $this->requestTimeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verifySSL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if ($this->requestUseCompression) {
            curl_setopt($ch, CURLOPT_ENCODING, "");
        }

        $output = curl_exec($ch);

        if ($output === false) {
            throw new ErrorException(curl_error($ch));
        }

        curl_close($ch);

        // get API response
        $rsp = json_decode($output, true);

        if ($rsp === null) {
            throw new ErrorException('Invalid JSON response: ' . json_last_error_msg());
        }


        if (array_key_exists('decodePolyline', $this->service) && $this->service['decodePolyline']) {

            // check if encoded polyline is present
            if (!$rsp['routes'][0]['polyline']['encodedPolyline']) {
                throw new ErrorException('Encoded Polyline not found: ' . json_encode($rsp));
            }

            // decode polyline
            $rsp['routes'][0]['polyline']['decodedPolyline'] =  PolyUtil::decode($rsp['routes'][0]['polyline']['encodedPolyline']);
        }


        return $rsp;
    }


    /**
     * Decode polyline
     *
     * @param string $encoded
     * @return array
     */
    public function decodePolyline($encoded)
    {
        $decoded = [];
        $index = 0;
        $len = strlen($encoded);
        $lat = 0;
        $lng = 0;

        while ($index < $len) {
            $shift = 0;
            $result = 0;
            do {
                $b = ord($encoded[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lat += $dlat;

            $shift = 0;
            $result = 0;
            do {
                $b = ord($encoded[$index++]) - 63;
                $result |= ($b & 0x1f) << $shift;
                $shift += 5;
            } while ($b >= 0x20);
            $dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
            $lng += $dlng;

            $decoded[] = [(float)$lat / 1e5, (float)$lng / 1e5];
        }

        return $decoded;
    }

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
    public function isLocationOnEdge($lat, $lng, $tolerance = 0.1)
    {

        $point = [
            'lat' => $lat,
            'lng' => $lng
        ];

        // get API response
        $rsp = $this->fetch();

        // check if encoded polyline is present
        if (!$rsp['routes'][0]['polyline']['encodedPolyline']) {
            throw new ErrorException('Encoded Polyline not found: ' . json_encode($rsp));
        }

        // decode polyline and check if location is on edge
        try {
            return PolyUtil::isLocationOnEdge($point, PolyUtil::decode($rsp['routes'][0]['polyline']['encodedPolyline']), $tolerance);
        } catch (ErrorException $e) {
            throw new ErrorException('Error decoding polyline: ' . $e->getMessage());
            return false;
        }
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
    public function containsLocation($lat, $lng)
    {

        $point = [
            'lat' => $lat,
            'lng' => $lng
        ];

        // get API response
        $rsp = $this->fetch();

        // check if encoded polyline is present
        if (!$rsp['routes'][0]['polyline']['encodedPolyline']) {
            throw new ErrorException('Encoded Polyline not found: ' . json_encode($rsp));
        }


        // decode polyline and check if contains location
        try {

            return PolyUtil::containsLocation($point, PolyUtil::decode($rsp['routes'][0]['polyline']['encodedPolyline']));
        } catch (ErrorException $e) {
            throw new ErrorException('Error decoding polyline: ' . $e->getMessage());
            return false;
        }
    }
}
