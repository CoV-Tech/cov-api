<?php namespace cov\utils\api\auth;


use cov\utils\api\rest\Endpoint;
use cov\utils\db\DB;

/**
 *
 * @author Ukhando
 *
 */
abstract class AuthEndpoint implements Endpoint{

    /**
     * @var Authenticator $authenticator
     */
    private $authenticator;

    /**
     * AuthEndpoint constructor.
     * @param Authenticator $authenticator
     */
    public function __construct( Authenticator $authenticator){
        $this->authenticator = $authenticator;
    }

    /**
     * @param string $username
     * @param string $password
     * @param DB $db
     * @return Token
     * @throws WrongUsernameOrPassword
     */
    public function login( string $username, string $password, DB $db) : Token{
        return $this->authenticator->login( $username, $password, $db);
    }

    /**
     * @param string $token
     * @param DB $db
     * @return mixed
     */
    public function logout( string $token, DB $db){
        return $this->authenticator->logout( $token, $db);
    }
}