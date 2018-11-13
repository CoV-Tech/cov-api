<?php namespace cov\utils\api\auth;

use cov\core\debug\Logger;
use cov\utils\api\auth\exceptions\WrongUsernameOrPassword;
use cov\utils\api\rest\Request;
use cov\utils\api\rest\Response;
use cov\utils\api\rest\Status;
use cov\utils\db\DB;

/**
 *
 * @author Ukhando
 *
 */
class LoginEndpoint extends AuthEndpoint{

    /**
     * LoginEndpoint constructor.
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
        if ($auth === null){
            return new Response( new Status(4920, "Authentication must be Basic", 401));
        }
        $autharr = explode( " ", $auth);
        $type = trim($autharr[0]);
        $usernamepassword = isset( $autharr[1]) ? base64_decode( $autharr[1]) : ":";
        $att = explode( ":", $usernamepassword);
        $username = isset( $att[0]) ? $att[0] : "";
        $password = isset( $att[1]) ? $att[1] : "";
        if ($type !== "Basic"){
            return new Response( new Status(4920, "Authentication must be Basic", 401));
        }
        try{
            $token = $this->login( $username, $password, $db);
        }catch( WrongUsernameOrPassword $e){
            return new Response( new Status( 60154, "Wrong username or password", 401));
        }
        return new Response( new Status( 1 , "OK", 200), array(
            "username" => $username,
            "token" => $token
        ));
    }
}