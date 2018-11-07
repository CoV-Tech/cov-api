<?php namespace cov\utils\api\rest\exceptions;

/**
 *
 * @author Ukhando
 *
 */
class FunctionNotSupported extends ApiException{
	
	/**
	 *
	 * @param string $message
	 * @param Exception $previous
	 */
	public function __construct( string $message = "", $previous = null){
		parent::__construct( $message, 62150, $previous);
	}
	
}
