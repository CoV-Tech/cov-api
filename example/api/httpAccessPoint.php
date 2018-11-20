<?php namespace example\api;

/* this file should always be called when accessing localhost/api/{version} */
/* you can do this by adding a .htaccess file */

/* if the .htaccess file is in the same directory as /cov/ and /example/ then you can add the following inside of it
 *
 * RewriteRule ^/(.*) example/api/httpAccessPoint.php [NC,L]
 * RewriteRule ^/ example/api/httpAccessPoint.php [NC,L]
 * RewriteRule ^ example/api/httpAccessPoint.php [NC,L]
 *
 * Options +FollowSymLinks
 * RewriteEngine On
 *
 */


require_once __DIR__."/../../cov/autoLoader.php"; // the api autoloader, loads everything under the namespace \cov\

use cov\core\debug\FileWriter;            // for logging to a file
use cov\utils\db\DB;                      // for the database handler
use cov\utils\api\nodes\Nodes;            // for the node handler
use cov\utils\api\nodes\HttpAccessPoint;  // the access point
use cov\utils\api\rest\RoutesConfig;      // for configuring routes


use example\api\auth\Auth;                // our auth implementation
use example\api\endpoints\StatusEndpoint; // our status endpoint


require_once __DIR__."/../autoLoader.php"; // our autoloader



/* Creating a new Database Handler */
$db = new DB();

/* Adding a mysql Database connection */

/* $db->addHost( name, hostName (ip Address), schema, username, password, mode (r : readOnly, w : write or read), driver(mysql or oci) ) */
$db->addHost( "mysql", "localhost", "example", "root", "", "w", "mysql");


/* Creating a node handler */
$nodes = new Nodes();

/*
 * nodes are accessible via /{node}/{id} and if it doesn't exist it will return a route not found
 * or via /node/{node}/{id} and if it doesn't exist it will return a node not found
 *
 */



/* creating a node called user */
$nodes->createNode( "user", new UserController());

/* lets add the field called "id" of type int to the node "user" */
$nodes->addFieldToNode( "user", "id", "int");

/* lets add the field called "first_name" of type string to the node "user" */
$nodes->addFieldToNode( "user", "first_name", "string");

/* lets add the field called "last_name" of type string to the node "user" */
$nodes->addFieldToNode( "user", "last_name", "string");



/* creating our routesConfig, it will be listening on localhost/api/{version}/ */
$routesConfig = new RoutesConfig( "/api/{version}/");

/* when adding a path with {} it creates a variable inside the request object */
/* you can access it by using $request->getPath("version") if the route has /{version}/ inside of it*/

/* adding a route on the url localhost/api/{version}/status via a GET request, when navigating there, it will call the main on StatusEndpoint */
$routesConfig->addRoute( "GET", "/status", new StatusEndpoint());


/* creating a simple logger that writes to /log.txt */
$logger = new FileWriter( __DIR__."/log.txt");

/* creating the access point */
$access = new HttpAccessPoint( $logger, "v1", $routesConfig, $nodes, $db, new Auth());
/* if we didn't want the Authentication, and just let everyone use our api, call :
 *
 * $access = new HttpAccessPoint( $logger, "v1", $routesConfig, $nodes, $db);
 *
 */







/* treat the request received */
$access->treatRequest();