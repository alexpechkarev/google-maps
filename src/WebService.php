<?php namespace GoogleMaps;

use ErrorException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

/**
 * Description of GoogleMaps
 * * @author Alexander Pechkarev <alexpechkarev@gmail.com>
 */
class WebService
{
    /*
    |--------------------------------------------------------------------------
    | Default Endpoint
    |--------------------------------------------------------------------------
    */
    protected $endpoint;

    /*
    |--------------------------------------------------------------------------
    | Web Service
    |--------------------------------------------------------------------------
    */
    protected $service;

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    */
    protected $key;

    /*
    |--------------------------------------------------------------------------
    | Service URL
    |--------------------------------------------------------------------------
    */
    protected $requestUrl;

    /*
    |--------------------------------------------------------------------------
    | Verify SSL Peer
    |--------------------------------------------------------------------------
    */
    protected $verifySSL;

    /*
    |--------------------------------------------------------------------------
    | Request's timeout
    |--------------------------------------------------------------------------
    */
    protected $requestTimeout;

    /*
    |--------------------------------------------------------------------------
    | Connection timeout
    |--------------------------------------------------------------------------
    */
    protected $connectionTimeout;

    /*
    |--------------------------------------------------------------------------
    | Request's compression setting
    |--------------------------------------------------------------------------
    */
    protected $requestUseCompression;

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
     * * @param string|false $needle - response key
     * @return string|array
     * @throws \ErrorException
     */
    public function get( $needle = false ){
        return empty( $needle ) ? $this->getResponse() : $this->getResponseByKey( $needle );
    }

    /**
     * Get response value by key
     * * @param string|bool $needle - retrieves response parameter using "dot" notation
     * @return array
     * @throws \ErrorException
     */
    public function getResponseByKey( $needle = false){
        $this->setEndpoint('json');
        $needle = empty( $needle ) ? metaphone($this->service['responseDefaultKey']) : metaphone($needle);
        
        $obj = json_decode( $this->get(), true);
        $obj_dot = Arr::dot($obj); 
        $response = [];
        
        foreach( $obj_dot as $key => $val){
            if( strcmp( metaphone($key, strlen($needle)), $needle) === 0 ){ 
                Arr::set($response, $key, $val); 
            } 
        }
        
        return count($response) < 1 ? $obj : $response;
    }

    /**
     * Get response status
     * * @return mixed
     * @throws \ErrorException
     */
    public function getStatus(){
        $this->setEndpoint('json');
        $obj = json_decode( $this->get(), true);
        return Arr::get($obj, 'status', null);
    }

    /**
     * Setup service parameters
     * * @param $service
     * @throws \ErrorException
     */
    protected function build( $service ){
        $this->validateConfig( $service );
        $this->setEndpoint();
        
        $this->service = Config::get('googlemaps.service.'.$service);
        $this->key = empty( $this->service['key'] ) ? Config::get('googlemaps.key') : $this->service['key'];
        $this->requestUrl = $this->service['url'];
        
        $this->verifySSL = Config::get('googlemaps.ssl_verify_peer', true);
        $this->connectionTimeout = Config::get("googlemaps.connection_timeout");
        $this->requestTimeout = Config::get("googlemaps.request_timeout");
        $this->requestUseCompression = Config::get("googlemaps.request_use_compression");
        
        $this->clearParameters();
    }

    /**
     * Validate configuration file
     * * @param $service
     * @throws \ErrorException
     */
    protected function validateConfig( $service ){
        if( ! Config::has('googlemaps')){ 
            throw new ErrorException('Unable to find config file.'); 
        }
        if(Config::has('googlemaps.key') === false){ 
            throw new ErrorException('Unable to find Key parameter in configuration file.'); 
        }
        if(Config::has('googlemaps.service') === false || Config::has('googlemaps.service.'.$service) === false){ 
            throw new ErrorException('Web service must be declared in the configuration file.'); 
        }
        
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
    protected function getResponse()
    {
        $post = false;
        
        // OPTIMIZATION: Isolate calculations to a local variable to prevent URL string compounding corruption bugs
        $executionUrl = $this->requestUrl;
        $executionUrl .= $this->service['endpoint'] ? $this->endpoint : '';
        $executionUrl .= 'key=' . urlencode($this->key);

        switch ($this->service['type']) {
            case 'POST':
                $post = json_encode($this->service['param']);
                break;
            default:
                $executionUrl .= '&' . Parameters::getQueryString($this->service['param']);
                break;
        }

        return $this->make($executionUrl, $post);
    }

    /**
     * Make cURL request to given URL
     * @param string $url
     * @param string|boolean $isPost
     * @return bool|string
     * @throws \ErrorException
     */
    protected function make($url, $isPost = false)
    {
        $ch = curl_init($url);
        
        // Define clean baseline header matrices
        $headers = [];

        if ($isPost) {
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $isPost);
        }

        // OPTIMIZATION: Check for and inject any custom fields defined in config headers (e.g. X-Goog-FieldMask)
        if (!empty($this->service['headers']) && is_array($this->service['headers'])) {
            foreach ($this->service['headers'] as $headerKey => $headerValue) {
                if (!empty($headerValue)) {
                    $headers[] = "{$headerKey}: {$headerValue}";
                }
            }
        }

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectionTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->requestTimeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        // OPTIMIZATION: Enforce complete host name common verification logic rules
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verifySSL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->verifySSL ? 2 : 0);

        if ($this->requestUseCompression) {
            curl_setopt($ch, CURLOPT_ENCODING, "");
        }

        $output = curl_exec($ch);

        if ($output === false) {
            throw new ErrorException(curl_error($ch));
        }

        curl_close($ch);
        return $output;
    }

    protected function clearParameters() { 
        Parameters::resetParams(); 
    }
}
