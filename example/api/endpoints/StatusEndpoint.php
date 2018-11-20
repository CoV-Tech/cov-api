<?php namespace example\api\endpoints;

use cov\core\debug\Logger as Logger;
use cov\utils\api\rest\Endpoint as Endpoint;
use cov\utils\api\rest\Response as Response;
use cov\utils\api\rest\Request as Request;
use cov\utils\api\rest\Status as Status;
use cov\utils\SystemInfo as SystemInfo;
use cov\utils\db\DB as DB;
use \PDOException as PDOException;


/* An example endpoint */
class StatusEndpoint implements Endpoint {
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \cov\utils\api\rest\Endpoint::main()
	 */
	/* the $logger gives us a way to log thing to a file using $logger->write( "the message", "DEBUG"|"ERROR"|"INFO") */
    /* the $request holds information about the request made */
    /* the $db is our database manager */
	public function main( Logger $logger, Request $request, DB $db){
		$disks = array("C:"); /* declaration of the disks, we only have C: */
		$query = "SELECT 'YES' RESPONSE FROM DUAL"; /* the default query */
		$databases = array();
		$hosts = $db->getHostNames(); /* getting all the database host names */
		foreach ( $hosts as $hostname){  /* looping through the databases */
			$start = microtime(true);
			try{
				$db->executeQuery($hostname, $query);
				$ret = $db->getResults(); /* the connection was successful */
				$databases[$hostname] = array( /* if the response is different from "YES" then an unknown error has occurred */
						"result" => (isset($ret[0]["RESPONSE"]) ? ($ret[0]["RESPONSE"] == "YES" ? "correct response" : "wrong response") : "no reponse"),
						"status" => "OK",
						"response-time" => round((microtime(true) - $start),3)
				);
			}catch(PDOException $e){ /* if error, it means the connection has failed */
				$databases[$hostname] = array(
						"error" => $e,
						"status" => "ERROR",
						"response-time" => round((microtime(true) - $start),3)
				);
			}
		}
		
		$system = new SystemInfo(); /* loading the system info class */
		$disk_info = array();
		foreach ($disks as $disk){
			$disk_info[$disk] = $system->getDiskSize($disk); /* get information of every disk */
		}
		$system_info = array(
				"os" => PHP_OS,
				"cpu_usage" => $system->getCpuLoadPercentage(),
				"ram" => array(
						"total" => $system->getRamTotal(),
						"free" => $system->getRamFree()
				),
				"disks" => $disk_info
		);
		
		
		/* a response is composed of a Status and data (the data must be jsonSerializable) */
		return new Response(Status::getStatus("OK"), array(
				"databases" => $databases,
				"system" => $system_info
		));
	}
	
}
