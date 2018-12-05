<?php namespace cov\utils\api\auth;

use cov\core\debug\Logger;
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
     * @param Logger $logger
     * @return Token
     * @throws WrongUsernameOrPassword
     */
    public function login(string $username, string $password, DB $db, Logger $logger) : Token{
        if (!$this->checkUsernamePasswordCombo( $username, $password, $db, $logger)){
            throw new WrongUsernameOrPassword();
        }
        do{
            $id = base64_encode(random_bytes( 100));
            $id = substr( $id, 0, 200);
        }while ($this->getToken($id, $db) !== null);
        $refresh = base64_encode(random_bytes( 100));
        $token = new Token( $id, $username, time(), true, $refresh);
        $this->storeToken( $token, $db);
        return $token;
    }

    /**
     * @param $token
     * @param $refreshToken
     * @param $db
     * @return Token|null
     */
    public function refresh( $token, $refreshToken, $db){
        $t = $this->getToken( $token, $db);
        if ($t === null || !$t->isValid()){
            return null;
        }
        if ($t->getRefresh() === $refreshToken){
            do{
                $id = base64_encode(random_bytes( 100));
                $id = substr( $id, 0, 200);
            }while ($this->getToken($id, $db) !== null);
            $refresh = base64_encode(random_bytes( 100));
            $token = new Token( $id, $t->getUsername(), time(), true, $refresh);
            $old = new Token( $t->getId(), $t->getUsername(), $t->getTimeGiven(), false, $t->getRefresh());
            $this->storeToken( $token, $db);
            $this->storeToken( $old, $db);
            return $token;
        }else{
            echo $t->getRefresh()."\n";
            echo $refreshToken."\n";
            return null;
        }
    }

    /**
     * @param string $token
     * @param DB $db
     */
    public function logout( string $token, DB $db){
        $t = $this->getToken( $token, $db);
        if ($t === null || !$t->isValid()){
            return false;
        }
        $n = new Token($t->getId(), $t->getUsername(), $t->getTimeGiven(), false, $t->getRefresh());
        $this->storeToken($n, $db);
        return true;
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
        if ($tok->getTimeGiven() > time()-3600){
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
     * @param Logger $logger
     * @return bool
     */
    public abstract function checkUsernamePasswordCombo( string $username, string $password, DB $db, Logger $logger) : bool;


}