<?php

/**
 * Description of Request
 *
 * @author Valeria
 */
class ApiRequest {
    
    	/**
	 * authentication token
	 * 
	 * @var $token
	 */
	public $token = null;
        
	public function setToken($token) {
            $this->token = $token;
        }
	/**
	 * Enter description here...
	 *
	 * @var unknown_type
	 */
	public $uri;
	
        public function setUri($uri) {
            $this->uri = $uri;
        }
	/**
	 * http request method
	 *
	 * @var string post, put, delete, get
	 */
	public $method = 'get';
	
        public function setMethod($method) {
            $this->method = strtolower($method);
        }
	/**
	 * data posted/put in the request
	 *
	 * @var string
	 */
	public $data = null;
        
        public function setData($data) {
            $this->data = $data;
        }
        
	/**
	 * if true, Caller will json_decode the response
	 *
	 * @var boolean
	 */
	public $decodeResponse = true;
        
        public function setDecodeResponse($decodeResponse) {
            $this->decodeResponse = $decodeResponse;
        }
        
	/**
	 * if true, Caller will save in memory the response
	 *
	 * @var boolean
	 */
	public $saveResponse = false;
        
        public function setSaveResponse($saveResponse) {
            $this->saveResponse = $saveResponse;
        }
        
	/**
	 * if json, Caller will json_ecode the data
         * if array, Caller will convert to array the data
	 *
	 * @var string
	 */
	public $ecodeData = "json";
        
        public function setEncodeData($format) {
            $this->ecodeData = $format;
        }
        
	/**
	 * if true, Caller will be Imonomy
	 *
	 * @var boolean
	 */
	public $isImonomy = false;
        
        public function setIsImonomy($isImonomy) {
            $this->isImonomy = $isImonomy;
        }
        
        /***
         * Validation method
         */
        public function validate() {
            if (empty($this->uri))
                throw new Exception("Request.uri could not be empty");
            
            if (empty($this->method)) 
                throw new Exception("Request.method could not be empty");
            
            if ('put' == $this->method || 'post' == $this->method) {
                if (empty($this->data)) 
                    throw new Exception("Request.method is " . $this->method . " but Request.data is empty");
            }
        }
}
