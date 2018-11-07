<?php namespace cov\utils\api\rest\exceptions;

use cov\utils\api\rest\exceptions\ApiException;

/**
 * 
 * @author Ukhando
 *
 */
class ParseException extends ApiException{
	
	/**
	 *
	 * @param Exception $previous
	 */
	public function __construct( $previous = null){
		parent::__construct( "field parsing failed", 62140, $previous);
	}
	
}
