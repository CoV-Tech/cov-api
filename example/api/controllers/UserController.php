<?php namespace example\api\controllers;

use cov\utils\api\nodes\NodeController as NodeController;
use cov\utils\db\DB as DB;
use cov\utils\api\rest\Field as Field;
use \PDO as PDO;


class UserController extends NodeController{
    // possible functions :
    /*
     * public function post( array $data, DB $database) : bool
     * gets called when we want to add an object
     *
     *
     * public function getAll( Field $fields, DB $database)
     * gets called when we want to get a list with all the objects
     *
     * public function get( string $id, Field $fields, DB $database)
     * gets called when we want to get a specific object
     *
     *
     * public function getFromParent( string $parent, string $parentid, Field $fields, DB $database)
     * for your own use if you want to implement getting a list from a parent id
     *
     *
     */
    // to get the controller of another node call $this->getController( string $node)


    /**
     * @param string $id
     * @param Field $field
     * @param DB $database
     * @return array|null
     * @throws \cov\core\exceptions\CovException
     */
    /* lets implement a simple get */
    /* lets imagine our database "mysql" has a table "users" with the columns "id","first_name","last_name" */
    public function get(string $id, Field $field, DB $database){
        $query = "SELECT ID";
        if ($field->isFieldSet("first_name")){ /* add the column first_name if the user asks for it */
            $query .= ",FIRST_NAME";
        }
        if ($field->isFieldSet("last_name")){ /* add the column last_name if the user asks for it */
            $query .= ",LAST_NAME";
        }
        $query .= " FROM users WHERE id = :id";

        /* now our query asks for the least amount of data to respond to the request */

        $database->executeQuery( "mysql", $query, array(
            ":id" => array( $id, PDO::PARAM_STR)
        ));

        /* let get the answer from the database */
        $results = $database->getResults();

        /* if the column ID isn't set, then no data was found, so we return null */
        if (!isset( $results[]["ID"])) {
            return null;
        }

        /* lets create our response object */
        $ret = array();

        if ($field->isFieldSet("id")){ /* if the id was asked, we add the id to the object */
            $ret["id"] = $results[]["ID"];
        }
        if ($field->isFieldSet("first_name")){  /* if the first name was asked, we add the first name to the object */
            $ret["first_name"] = $results[]["FIRST_NAME"];
        }
        if ($field->isFieldSet("last_name")){  /* if the last name was asked, we add the last name to the object */
            $ret["last_name"] = $results[]["LAST_NAME"];
        }

        return $ret; /* we return the object */
    }




}
