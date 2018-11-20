<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger;
use cov\utils\api\auth\Authenticator;
use cov\utils\db\DB;

/**
 *
 * @author Ukhando
 *
 */
class DefaultEndpoint implements Endpoint {

    private $auth;

    public function __construct(Authenticator $auth = null){
        $this->auth = $auth;
    }

    /**
	 *
	 * {@inheritDoc}
	 * @see \cov\utils\api\rest\Endpoint::main()
	 */
	public function main( Logger $logger, Request $request, DB $db){
		return new Response( 
				Status::getStatus("OK"),
				array(
				    "time" => time(),
                    "auth" => $this->auth === null ? null : $this->auth->getRealm()
				)
		);
	}
	
}