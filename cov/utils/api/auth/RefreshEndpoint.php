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
class RefreshEndpoint extends AuthEndpoint{

    /**
     * RefreshEndpoint constructor.
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
        $refresh = $request->getParameter( "refresh");
        if ($token === null || $refresh === null){
            return Response::createFromStatus("not authorized");
        }
        $refresh = str_replace( " ", "+", $refresh);
        $newToken = $this->refresh( $token, $refresh, $db);
        if ($newToken !== null){
            return Response::createFromStatus("OK", array("token" => $newToken));
        }else{
            return Response::createFromStatus("not authorized", array( "token" => $token, "refresh" => $refresh));
        }
    }
}