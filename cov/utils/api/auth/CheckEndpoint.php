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
class CheckEndpoint extends AuthEndpoint{

    /**
     * CheckEndpoint constructor.
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
        $auth = $request->getHeader( "Authorization");
        $autharr = $auth !== null ? explode( " ", $auth) : null;
        $token = isset( $autharr[1]) ?  $autharr[1] : null;
        if ($token !== null && $this->check($token, $db)){
            return Response::createFromStatus("OK");
        }else{
            return Response::createFromStatus("not authorized");
        }
    }
}