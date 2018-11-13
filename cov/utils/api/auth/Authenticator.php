<?php namespace cov\utils\api\auth;

use cov\utils\api\auth\exceptions\WrongUsernameOrPassword;
use cov\utils\db\DB;

/**
 *
 * @author Ukhando
 *
 */
abstract class Authenticator
{

    /**
     * @var string realm
     */
    private $realm;

    /**
     * Authenticator constructor.
     * @param string $realm
     */
    public function __construct( string $realm){
        $this->realm = $realm;
    }

    /**
     * @return string
     */
    public function getRealm( ) : string{
        return $this->realm;
    }

    /**
     * @param string $username
     * @param string $password
     * @param DB $db
     * @return Token
     * @throws WrongUsernameOrPassword
     */
    public function login(string $username, string $password, DB $db) : Token{
        if (!$this->checkUsernamePasswordCombo( $username, $password, $db)){
            throw new WrongUsernameOrPassword();
        }
        do{
            $id = base64_encode(random_bytes( 100));
            $id = substr( $id, 0, 200);
        }while ($this->getToken($id, $db) !== null);
        $token = new Token( $id, $username, time());
        $this->storeToken( $token, $db);
        return $token;
    }

    /**
     * @param string $token
     * @param DB $db
     * @return bool
     */
    public function isTokenValid( string $token, DB $db){
        $tok = $this->getToken( $token, $db);
        if ($tok === null){
            return false;
        }
        if (!$tok->isValid()){
            return false;
        }
        if ($tok->getGivenTime() > time()-3600){
            return true;
        }
        return false;
    }

    /**
     * @param Token $token
     * @param DB $db
     * @return mixed
     */
    public abstract function storeToken( Token $token, DB $db);

    /**
     * @param string $token_id
     * @param DB $db
     * @return Token
     */
    public abstract function getToken( string $token_id, DB $db);

    /**
     * @param string $username
     * @param string $password
     * @param DB $db
     * @return bool
     */
    public abstract function checkUsernamePasswordCombo( string $username, string $password, DB $db) : bool;

    public function logout($token, $db){}

}