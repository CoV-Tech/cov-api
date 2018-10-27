<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger as Logger;
use \JsonSerializable as JsonSerializable;

/**
 * 
 * @author Ukhando
 *
 */
class Route implements JsonSerializable{
	
	/**
	 *
	 * @var string $baseurl
	 * @var string $url
	 * @var Endpoint $endpoint
	 * @var string $method
	 */
	private $baseurl, $url, $endpoint, $method;
	
	/**
	 *
	 * @param string $method
	 * @param string $url
	 * @param Endpoint $endpoint
	 */
	public function __construct( string $method, string $baseurl, string $url, Endpoint $endpoint){
		$this->baseurl = trim( $baseurl, "/");
		$this->method = $method;
		$this->url = trim($this->baseurl."/".$url, "/");
		$this->endpoint = $endpoint;
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
	 * @return string
	 */
	public function getUrl(){
		return $this->url;
	}
	
	/**
	 *
	 * @return Endpoint
	 */
	public function getEndpoint(){
		return $this->endpoint;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getBaseUrl(){
		return $this->baseurl;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function parse( Logger $logger, string $url){
		$arr = array();
		$rurl = explode("/", $this->url);
		$curl = explode("/", trim($url, "/"));
		$i = 0;
		if (count($curl) != count($rurl)){
			return null;
		}
		while ($i < count($curl)){
			if (isset($rurl[$i])){
				if (preg_match("/^\{.*\}$/", $rurl[$i])){
					$key = ltrim(rtrim($rurl[$i], "}"), "{");
					$arr[$key] = $curl[$i];
				}elseif ($rurl[$i] != $curl[$i]){
					return null;
				}
			}
			$i++;
		}
		return $arr;
	}
	
	/**
	 * 
	 * @param Route $route
	 * @param string $url
	 * @return boolean
	 */
	public static function cmp( Route $route, $url){
		$rurl = explode("/", $route->getUrl());
		$curl = explode("/", trim($url, "/"));
		$offset = substr_count( $route->getBaseUrl(), "/");
		$i = 0;
		if (count($curl) != count($rurl)){
			return -1;
		}
		$min = 0;
		for ($i = 0; $i < count($curl); $i++){
			if (isset($rurl[$i])){
				if (preg_match("/^\{.*\}$/", $rurl[$i])){
					if ($min == 0 && $i > $offset){
						$min = $i;
					}
				}else{
					if ($rurl[$i] != $curl[$i]){
						return -1;
					}
				}
			}else{
				return -1;
			}
		}
		return $min;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize(){
		return array(
				"method" => $this->method,
				"url" => $this->url,
				"endpoint" => str_replace( "\\", "/", get_class($this->endpoint))
		);
	}
}