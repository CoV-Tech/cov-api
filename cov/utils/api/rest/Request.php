<?php namespace cov\utils\api\rest;

/**
 * 
 * @author Ukhando
 *
 */
class Request {
	
	/**
	 * 
	 * @var array $url
	 * @var array $headers
	 * @var string $body
	 * @var array $parameters
	 * @var string $method
	 * @var array $path
	 * @var Field $fields
	 */
	private $url, $headers, $body, $parameters, $method, $path, $fields;
	
	/**
	 * 
	 * @param array $url
	 * @param array $headers
	 * @param string $body
	 * @param array $parameters
	 * @param string $method
	 */
	public function __construct( array $url, array $headers, string $body, array $parameters, string $method, Field $fields){
		$this->url = $url;
		$this->headers = $headers;
		$this->body = $body;
		$this->parameters = $parameters;
		$this->method = $method;
		$this->path = array();
		$this->fields = $fields;
	}
	
	/**
	 * 
	 * @return Field
	 */
	public function getFields(){
		return $this->fields;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getMethod(){
		return $this->method;
	}
	
	/**
	 * 
	 * @param array $path
	 */
	public function parsedUrl( array $path){
		$this->path = $path;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getParameters(){
		return $this->parameters;
	}
	
	/**
	 * 
	 * @param string $key
	 * @return NULL|mixed
	 */
	public function getParameter( string $key){
		return isset($this->parameters[$key]) ? $this->parameters[$key] : null;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getBody(){
		return $this->body;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getHeaders(){
		return $this->headers;
	}
	
	/**
	 * 
	 * @param string $key
	 * @return NULL|mixed
	 */
	public function getHeader( string $key){
		return isset($this->headers[$key]) ? $this->headers[$key] : null;
	}

	public function getToken( ){
	    return "";
    }
	
	/**
	 * 
	 * @return array
	 */
	public function getPaths(){
		return $this->path;
	}
	
	/**
	 * 
	 * @param string $key
	 * @return NULL|mixed
	 */
	public function getPath( string $key){
		return isset($this->path[$key]) ? $this->path[$key] : null;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getUrl() : string{
		return implode( "/", $this->url);
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getUrlArr(){
		return $this->url;
	}
	
	/**
	 * 
	 * @param int $key
	 * @return NULL|mixed
	 */
	public function getUrlIndex( int $key){
		return isset($this->url[$key]) ? $this->url[$key] : null;
	}
	
}