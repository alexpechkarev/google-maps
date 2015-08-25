<?php namespace App\GoogleMaps;

/**
 * Description of WebService
 *
 * @author Alexander Pechkarev
 */

class WebService{
    
    /*
    |--------------------------------------------------------------------------
    | Response Default Key
    |--------------------------------------------------------------------------
    |
    |
    |
    */     
    private $defaultKey;     
    
    
    /**
     * Class constructor
     */
    public function __construct( $defaultKey )
      {
          $this->defaultKey = $defaultKey;                         
      }
      /***/

      
      
    /*
    |--------------------------------------------------------------------------
    | Public methods
    |--------------------------------------------------------------------------
    |
    */
      
     
    /**
     * Set parameter by the key
     * @param string $key
     * @param mixed $value
     * @return \App\GoogleMaps\WebService
     */
     public function setParamByKey($key, $value){

         \GM::setParamByKey($key, $value);
         
         return $this;
     }
     /***/ 
    
    /**
     * Get parameter by the key
     * @param string $key
     * @return mixed
     */ 
    public function getParamByKey($key){
        
        return \GM::getParamByKey($key);
         
    }
    /***/
    
    /**
     * Return parameters array
     * @return array
     */
    public function getParam(){
        return \GM::getParam();
    }
    /***/
   
      
    /**
     * Setting endpoint
     * @param string $key
     * @return \App\GoogleMaps\WebService
     */
    public function setEndpoint( $key = 'json' ){
         
        \GM::setEndpoint($key);
        return $this;
    }
    /***/

    /**
     * Get endpoint
     * @return type
     */
    public function getEndpoint(){
        
        return \GM::getEndpoint();
    }
    /***/
      
    /**
     * Get response
     * @return string
     */
    public function get(){
        
        return \GM::get();
    }
    /***/
        
    
    /**
     * Get response value by key
     * @param string $key - retrives response parameter using "dot" notation
     * @param int $offset 
     * @param int $length
     * @return array
     */
    public function getByKey( $key = false, $offset = 0, $length = null ){
        
        // set respons to json
        \GM::setEndpoint('json');
        
        // set default key parameter
        $key = empty( $key ) ? $this->defaultKey : $key;
        
        // get response
        $obj = json_decode( \GM::get(), true);
        
        $response = [];
        
        // is response OK
        if( str_is('OK', $obj['status']) ){
            
            // iterate results
            foreach($obj as $k => $v){
                 dd($v);
                // get place_id
                array_push($response, array_get($v, $key, null));
                
            }

            return array_slice($response, $offset, $length);
            
        }else{
            return $obj;
        }
        
    }
    /***/ 
    
    /**
     * Get response status
     * @return mixed
     */
    public function getStatus(){
        
        // set response to json
        \GM::setEndpoint('json');
        
        // get response
        $obj = json_decode( \GM::get(), true);
        
        return array_get($obj, 'status', null);
        
    }
    /***/
    

    
}

