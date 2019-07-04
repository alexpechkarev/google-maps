<?php namespace GoogleMaps;

use ErrorException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

/**
 * Description of GoogleMaps
 *
 * @author Alexander Pechkarev <alexpechkarev@gmail.com>
 */
class WebService{


    /*
    |--------------------------------------------------------------------------
    | Default Endpoint
    |--------------------------------------------------------------------------
    |
    */
    protected $endpoint;



    /*
    |--------------------------------------------------------------------------
    | Web Service
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    protected $service;


    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    protected $key;


    /*
    |--------------------------------------------------------------------------
    | Service URL
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    protected $requestUrl;

    /*
    |--------------------------------------------------------------------------
    | Verify SSL Peer
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    protected $verifySSL;

    /**
     * Setting endpoint
     * @param string $key
     * @return $this
     */
    public function setEndpoint( $key = 'json' ){

        $this->endpoint = Config::get("googlemaps.endpoint.{$key}", 'json?');

        return $this;
    }

    /**
     * Getting endpoint
     * @return string
     */
    public function getEndpoint( ){

        return $this->endpoint;
    }

    /**
     * Set parameter by key
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setParamByKey($key, $value){

         if( array_key_exists( $key, Arr::dot( $this->service['param'] ) ) ){
             Arr::set($this->service['param'], $key, $value);
         }

         return $this;
    }

    /**
     * Get parameter by the key
     * @param string $key
     * @return string|null
     */
    public function getParamByKey($key){
        return Arr::get($this->service['param'], $key, null);
    }

    /**
     * Set all parameters at once
     * @param array $param
     * @return $this
     */
    public function setParam( $param ){

        $this->service['param'] = array_merge( $this->service['param'], $param );

        return $this;
    }

    /**
     * Return parameters array
     * @return array
     */
    public function getParam(){
        return $this->service['param'];
    }

    /**
     * Get Web Service Response
     *
     * @param string|false $needle - response key
     * @return string|array
     * @throws \ErrorException
     */
    public function get( $needle = false ){

        return empty( $needle )
                ? $this->getResponse()
                : $this->getResponseByKey( $needle );
    }

    /**
     * Get response value by key
     *
     * @param string|bool $needle - retrieves response parameter using "dot" notation
     * @return array
     * @throws \ErrorException
     */
    public function getResponseByKey( $needle = false){

        // set response to json
        $this->setEndpoint('json');

        // set default key parameter
        $needle = empty( $needle )
                    ? metaphone($this->service['responseDefaultKey'])
                    : metaphone($needle);

        // get response
        $obj = json_decode( $this->get(), true);

        // flatten array into single level array using 'dot' notation
        $obj_dot = Arr::dot($obj);
        // create empty response
        $response = [];
        // iterate
        foreach( $obj_dot as $key => $val){

            // Calculate the metaphone key and compare with needle
            if( strcmp( metaphone($key, strlen($needle)), $needle) === 0 ){
                // set response value
                Arr::set($response, $key, $val);
            }
        }

        // finally extract slice of the array
        #return array_slice($response, $offset, $length);

        return count($response) < 1
               ? $obj
               : $response;
    }

    /**
     * Get response status
     *
     * @return mixed
     * @throws \ErrorException
     */
    public function getStatus(){

        // set response to json
        $this->setEndpoint('json');

        // get response
        $obj = json_decode( $this->get(), true);

        return Arr::get($obj, 'status', null);
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Setup service parameters
     *
     * @param $service
     * @throws \ErrorException
     */
    protected function build( $service ){

            $this->validateConfig( $service );

            // set default endpoint
            $this->setEndpoint();

            // set web service parameters
            $this->service = Config::get('googlemaps.service.'.$service);

            // is service key set, use it, otherwise use default key
            $this->key = empty( $this->service['key'] )
                         ? Config::get('googlemaps.key')
                         : $this->service['key'];

            // set service url
            $this->requestUrl = $this->service['url'];

            // is ssl_verify_peer key set, use it, otherwise use default key
            $this->verifySSL = empty(Config::get('googlemaps.ssl_verify_peer'))
                            ? FALSE
                            : Config::get('googlemaps.ssl_verify_peer');

            $this->clearParameters();
    }

    /**
     * Validate configuration file
     *
     * @param $service
     * @throws \ErrorException
     */
    protected function validateConfig( $service ){

            // Check for config file
            if( ! Config::has('googlemaps')){
                throw new ErrorException('Unable to find config file.');
            }

            // Validate Key parameter
            if(Config::has('googlemaps.key') === false){
                throw new ErrorException('Unable to find Key parameter in configuration file.');
            }

            // Validate Key parameter
            if(Config::has('googlemaps.service') === false || Config::has('googlemaps.service.'.$service) === false){
                throw new ErrorException('Web service must be declared in the configuration file.');
            }

            // Validate Endpoint
            $endpointCount = count(Config::get('googlemaps.endpoint', []));
            $endpointsKeyExists = Config::has('googlemaps.endpoint');

            if($endpointsKeyExists === false || $endpointCount < 1){
                throw new ErrorException('End point must not be empty.');
            }
    }

    /**
     * Get Web Service Response
     * @return string
     * @throws \ErrorException
     */
    protected function getResponse(){

        $post = false;

        // use output parameter if required by the service
        $this->requestUrl.= $this->service['endpoint']
                            ? $this->endpoint
                            : '';

        // set API Key
        $this->requestUrl.= 'key='.urlencode( $this->key );

        switch( $this->service['type'] ){
            case 'POST':
                $post = json_encode( $this->service['param'] );
                break;
            default:
                $this->requestUrl.='&'. Parameters::getQueryString( $this->service['param'] );
                break;
        }

        return $this->make( $post );
    }

    /**
     * Make cURL request to given URL
     * @param boolean $isPost
     * @return object
     * @throws \ErrorException
     */
    protected function make( $isPost = false ){

        $ch = curl_init( $this->requestUrl );

        if( $isPost ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch,CURLOPT_POST, 1);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $isPost );
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verifySSL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $output = curl_exec($ch);

        if( $output === false ){
            throw new ErrorException( curl_error($ch) );
        }

        curl_close($ch);
        return $output;
    }

    protected function clearParameters()
    {
        Parameters::resetParams();
    }
}
