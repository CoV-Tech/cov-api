<?php namespace example\api\auth;

use cov\core\debug\Logger;
use cov\utils\api\auth\Authenticator;
use cov\utils\api\auth\Token;
use cov\utils\db\DB;

class Auth extends Authenticator{

    /**
     *  constructor.
     */
    public function __construct(){
        parent::__construct( "example auth");
    }

    /**
     * @param Token $token
     * @param DB $db
     */
    public function storeToken(Token $token, DB $db){
        /* lets just write the tokens to a file */
        $file = fopen( $token->getId().".token", "w"); /* under the name id.token */
        $json = json_encode( $token); /* lets put the token in json format */
        fwrite( $file, $json); /* we write it to the file */
        fclose( $file); /* and of course, let's not forget to close the file */
    }

    /**
     * @param string $token_id
     * @param DB $db
     * @return Token
     */
    public function getToken(string $token_id, DB $db){
        /* lets check if the token id exists */
        if (!file_exists( $token_id.".token")){
            return null; /* return null if the token doesn't exists */
        }
        $file = fopen( $token_id.".token", "r");/* lets open the file */
        $jsonString = fgets( $file); /* lets get our stored json string */
        fclose( $file); /* lets close our file */
        $json = json_encode( $jsonString); /* lets create a json object from our string */

        $token = new Token( /* lets create the token */
            $json->id,
            $json->username,
            $json->time_given,
            $json->valid
        );

        return $token; /* and return our token */
    }

    /**
     * @param string $username
     * @param string $password
     * @param DB $db
     * @param Logger $logger
     * @return bool
     */
    public function checkUsernamePasswordCombo(string $username, string $password, DB $db, Logger $logger): bool{
        /* we are going to just let the user "user" with the password "password" login */
        return ($username === "user" && $password === "password");
        /* return true if the username/password combo is valid, else return false */
    }
}