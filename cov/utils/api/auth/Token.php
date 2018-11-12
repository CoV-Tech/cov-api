<?php namespace cov\utils\api\auth;

use \JsonSerializable;

/**
 *
 * @author Ukhando
 *
 */
class Token implements JsonSerializable {


    /**
     * @var string $id
     * @var string $username
     * @var int $time_given
     * @var bool $valid
     */
    private $id, $username, $time_given, $valid;

    /**
     * Token constructor.
     * @param string $id
     * @param string $username
     * @param int $time_given
     * @param bool $valid
     */
    public function __construct( string $id, string $username, int $time_given, bool $valid = true){
        $this->id = $id;
        $this->username = $username;
        $this->time_given = $time_given;
        $this->valid = $valid;
    }

    /**
     * Getter for id
     * @return string
     */
    public function getId() : string{
        return $this->id;
    }

    /**
     * Getter for valid
     * @return bool
     */
    public function isValid() : bool{
        return $this->valid;
    }

    /**
     * Getter for username
     * @return string
     */
    public function getUsername() : string{
        return $this->username;
    }

    /**
     * Getter for time_given
     * @return int
     */
    public function getTimeGiven() : int{
        return $this->time_given;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(){
        return array(
            "id" => $this->getId(),
            "username" => $this->getUsername(),
            "time_given" => $this->getTimeGiven(),
            "valid" => $this->isValid()
        );
    }
}