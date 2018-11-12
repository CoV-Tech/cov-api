<?php namespace cov\utils\api\auth;

use cov\core\debug\Logger;
use cov\utils\api\rest\Request;
use cov\utils\api\rest\Response;
use cov\utils\db\DB;

/**
 *
 * @author Ukhando
 *
 */
class LogoutEndpoint extends AuthEndpoint{

    /**
     * LogoutEndpoint constructor.
     * @param Authenticator $authenticator
     */
    public function __construct( Authenticator $authenticator){
        parent::__construct( $authenticator);
    }


    /**
     *
     * @param Logger $logger
     * @param Request $request
     * @param DB $db
     * @return Response
     */
    public function main(Logger $logger, Request $request, DB $db){
        $token = $request->getHeader( "token");


    }
}