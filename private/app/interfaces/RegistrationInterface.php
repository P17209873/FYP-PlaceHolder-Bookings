<?php
/**
 * RegistrationInterface.php
 *
 * Defines an interface for all classes related to registering a new user in the database.
 *
 * @author Matthew Trim <p17209873@my365.dmu.ac.uk>
 */

namespace PlaceHolder\Interfaces;

/**
 * Interface RegistrationInterface
 * @package PlaceHolder
 */
interface RegistrationInterface
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
     * Performs the SQL Query necessary to insert newly created user data into the application database.
     *
     * @param $cleaned_username
     * @param $hashed_password
     * @param $cleaned_firstname
     * @param $cleaned_lastname
     * @param $cleaned_email
     * @return bool
     */
    public function createNewUser($cleaned_username, $hashed_password, $cleaned_firstname,
                                  $cleaned_lastname,$cleaned_email) : bool;

    /**
     * Checks the database to check whether the username passed in already exists.
     *
     * @param $cleaned_username
     * @return mixed string|boolean
     */
    public function doesUsernameExist($cleaned_username);

    /**
     * Checks the database to check whether the email passed in already exists.
     *
     * @param $cleaned_email
     * @return mixed string|boolean
     */
    public function doesEmailExist($cleaned_email);
}