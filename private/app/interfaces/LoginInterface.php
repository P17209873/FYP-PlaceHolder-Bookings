<?php
/**
 * LoginInterface.php
 *
 * Defines an interface for all classes related to logging in as an existing user.
 *
 * @author Matthew Trim <p17209873@my365.dmu.ac.uk>
 */

namespace PlaceHolder\Interfaces;

use phpDocumentor\Reflection\Types\Boolean;

/**
 * Interface LoginInterface
 * @package PlaceHolder
 */
interface LoginInterface
{
    /**
     * Sets the database wrapper using parameters. In all cases, the database wrapper should be accessed from the
     * object stored in the application container.
     *
     * @param $database_wrapper
     */
    public function setDatabaseWrapper($database_wrapper) : void;

    /**
     * Sets the database connection settings using parameters. In all cases, the database connection settings should
     * be accessed from the object stored in the application container.
     *
     * @param $database_connection_settings
     */
    public function setDatabaseConnectionSettings($database_connection_settings) : void;

    /**
     * Sets the database SQL queries using parameters. In all cases, the SQL queries should be accessed from the object
     * stored in the application container.
     *
     * @param $sql_queries
     */
    public function setSqlQueries($sql_queries) : void;

    /**
     * Stores the login attempt in the database.
     *
     * @param $user_id
     * @param $login_result
     */
    public function storeLoginAttempt($user_id, $login_result) : bool;

    /**
     * Retrieves the user password from the database.
     *
     * @param $user_id
     * @param $username
     * @return string
     */
    public function getUserPassword($user_id, $username) : string;

    /**
     * Retrieves the user ID from the database.
     *
     * @param $username
     * @return string
     */
    public function getUserID($username) : string;

    /**
     * Retrieves the user's details from the User table in the database.
     *
     * @param $username
     * @return array
     */
    public function getUserDetails($username) : array;

}