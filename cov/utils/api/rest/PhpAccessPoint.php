<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger;
use cov\utils\api\auth\Authenticator;
use cov\utils\api\auth\LoginEndpoint;
use cov\utils\db\DB;
use \Exception as Exception;

/**
 * 
 * @author Ukhando
 *
 */
class PhpAccessPoint {
	
	/**
	 * @var RoutesConfig $routes
	 * @var Logger $logger
	 * @var DB $db
     * @var string[] $devs
	 */
	private $routes, $logger, $db, $auth, $devs;

    /**
     *
     * @param Logger $logger
     * @param RoutesConfig $routes
     * @param DB $db
     * @param Authenticator|null $auth
     */
	public function __construct( Logger $logger, RoutesConfig $routes, DB $db, Authenticator $auth = null){
	    $this->devs = array();
		$routes->addRoute( "GET", "", new DefaultEndpoint($auth));
		if ($auth !== null){
		    $routes->addRoute( "POST", "auth/login",  new LoginEndpoint(  $auth)); /* LOGIN   */
            //$routes->addRoute( "GET",  "auth/logout", new LogoutEndpoint( $auth)); /* LOGOUT  */
            //$routes->addRoute( "GET",  "auth/token",  null); /* CHECK   */
            //$routes->addRoute( "POST", "auth/token",  null); /* REFRESH */
            $this->devs[] = $routes->getBaseUrl()."/auth/login";
        }
        $this->devs[] = $routes->getBaseUrl();
		if (!$routes->routeExists( "GET", "dev")){
			$routes->addRoute("GET",  "dev", new DevEndpoint( $routes));
		}
		$this->routes = $routes;
		$this->logger = $logger;
		$this->db     = $db;
		$this->auth   = $auth;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getBaseUrl(){
		return $this->routes->getBaseUrl();
	}
	
	/**
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function main( Request $request){
		$route = null;
		try{
			$route = $this->routes->getRoute($this->logger, $request->getUrl(), $request->getMethod());
		}catch( MultipleRoutesException $e){
			$this->logger->write( "multiple routes available for : ".$request->getMethod()." ".$request->getUrl(), "ERROR");
			return Response::createFromStatus("multiple routes");
		}
		if ($route === null){
			$this->logger->write( "route ".$request->getMethod()." ".$request->getUrl()."  not supported", "ERROR");
			return Response::createFromStatus("route not supported");
		}
		if ($route->getEndpoint() === null){
			$this->logger->write( "unknown route error, no endPoint found for : ".$request->getMethod()." ".$request->getUrl(), "ERROR");
			return Response::createFromStatus("unknown route error, no endPoint found");
		}
		$request->parsedUrl( $route->parse( $this->logger, $request->getUrl()));
		$response = null;

		if (!in_array($route->getUrl(), $this->devs) && $this->auth !== null){
		    $token = $request->getToken();
		    if ($token === null || !$this->auth->isTokenValid( $request->getToken(), $this->db)){
		        return Response::createFromStatus( "not authorized", array( "login_url" => $route->getBaseUrl()."/auth/login"));
            }
        }


		try{
			$response = $route->getEndpoint()->main( $this->logger, $request, $this->db);
		}catch( Exception $e){
			$stack = "";
			$i = 0;
			foreach( $e->getTrace() as $trace){
				$stack .= "#".$i." ".$trace["class"]."(".$trace["line"]."): ".$trace["function"]."\n";
				$i++;
			}
			$response = new Response( new Status(-4, $e->getMessage(), 500), $stack);
		}
		if ($response == null || $response->getStatus() == null){
			$this->logger->write( "unknown route error, no response given for route : ".$request->getMethod()." ".$request->getUrl(), "ERROR");
			return Response::createFromStatus("unknown route error, no response given");
		}
		$this->logger->write( "request succesfull : ".$request->getMethod()." ".$request->getUrl(), "INFO");
		return $response;
	}
}