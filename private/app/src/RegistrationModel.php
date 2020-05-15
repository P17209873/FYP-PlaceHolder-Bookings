<?php


namespace PlaceHolder;


use PlaceHolder\Interfaces\RegistrationInterface;

class RegistrationModel implements RegistrationInterface
{

    private $database_wrapper;
    private $database_connection_settings;
    private $sql_queries;

    public function __construct(){}
    public function __destruct(){}

    /**
     * @inheritDoc
     */
    public function setDatabaseWrapper($database_wrapper): void
    {
        $this->database_wrapper = $database_wrapper;
    }

    /**
     * @inheritDoc
     */
    public function setDatabaseConnectionSettings($database_connection_settings): void
    {
        $this->database_connection_settings = $database_connection_settings;
    }


    /**
     * @inheritDoc
     */
    public function setSqlQueries($sql_queries): void
    {
        $this->sql_queries = $sql_queries;
    }

    /**
     * @inheritDoc
     */
    public function createNewUser($cleaned_username, $cleaned_email, $cleaned_firstname,
                                  $cleaned_lastname, $hashed_password): bool
    {

        $query_string = $this->sql_queries->createNewUser();

        $query_params = [':Username' => $cleaned_username, ':UserPassword' => $hashed_password,
            ':UserEmail' => $cleaned_email, ':UserFirstName' => $cleaned_firstname,
            ':UserLastName' => $cleaned_lastname, ':UserTypeID' => 2]; // 2 signifies normal user

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        //switches the value of the boolean to make for a more user friendly codebase - if result is false, the query executed successfully, inverting this to true infers this better
        if ($result == false) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @inheritDoc
     */
    public function doesUsernameExist($cleaned_username)
    {
        $query_string = $this->sql_queries->getUserId();
        $query_params = [':Username' => $cleaned_username];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == true) {
            return 'Unfortunately there has been a query error';
        }
        else {
            $result = $this->database_wrapper->safeFetchArray();
            if($result != null) {
                return true;
            }
            else {
                return false;
            }
        }

    }

    /**
     * @inheritDoc
     */
    public function doesEmailExist($cleaned_email)
    {
        $query_string = $this->sql_queries->getUserEmail();
        $query_params = [':UserEmail' => $cleaned_email];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == true) {
            return 'Unfortunately there has been a query error';
        }
        else {
            $result = $this->database_wrapper->safeFetchArray();
            if($result != null) {
                return true;
            }
            else {
                return false;
            }
        }
    }
}