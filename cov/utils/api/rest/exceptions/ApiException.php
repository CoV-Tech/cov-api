<?php namespace cov\utils\api\rest\exceptions;

use cov\core\exceptions\CovException as CoVException;

/**
 * 
 * @author Ukhando
 *
 */
class ApiException extends CoVException{
	
	/**
	 * 
	 * @param string $message
	 * @param int $code
	 * @param Exception $previous
	 */
	public function __construct( string $message = "", int $code = 0, $previous = null){
		parent::__construct( $message, $code, $previous);
	}
	
}
