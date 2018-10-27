<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger as Logger;
use cov\core\exceptions\CovException as Exception;

/**
 * 
 * @author Ukhando
 *
 */
class RoutesConfig {
	
	/**
	 * 
	 * @var Route[] $routes
	 * @var string $base
	 */
	private $routes, $base;
	
	public function __construct( string $base = "api"){
		$this->routes = array();
		$this->base = trim($base, "/");
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getBaseUrl(){
		return $this->base;
	}
	
	/**
	 * 
	 * @param string $url
	 * @param Endpoint $endpoint
	 */
	public function addRoute( string $method, string $url, Endpoint $endpoint){
		$this->routes[] = new Route( $method, $this->base, trim($url, "/"), $endpoint);
	}
	
	public function getAllRoutes(){
		return $this->routes;
	}
	
	/**
	 * 
	 * @param string $url
	 */
	public function removeRoute( string $url){
		
	}
	
	/**
	 * 
	 * @param string $url
	 * @return Route[]
	 */
	public function getRoutes( string $url){
		$routes = array();
		foreach ($this->routes as $route){
			if (Route::cmp($route, $url)){
				$routes[] = $route;
			}
		}
		return $routes;
	}
	
	/**
	 * @param string $url
	 * @return Route
	 */
	public function getRoute( Logger $logger, string $url, string $method){
		$theRoute = array("min" => 0, "route" => null);
		foreach ($this->routes as $route){
			if ($route->getMethod() == $method){
				$i = Route::cmp($route, $url);
				if ($i >= 0){
					if ($theRoute["route"] == null || ($theRoute["min"] < $i && $theRoute["min"] != 0) || $i == 0){
						$theRoute["route"] = $route;
						$theRoute["min"] = $i;
					}elseif( $theRoute["route"] != null && ($theRoute["min"] == $i && $theRoute["min"])){
						throw new MultipleRoutesException();
					}
				}
			}
		}
		return $theRoute["route"];
	}
	
	
}

class MultipleRoutesException extends Exception{
	
}