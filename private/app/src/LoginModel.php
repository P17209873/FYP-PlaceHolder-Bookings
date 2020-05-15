<?php


namespace PlaceHolder;


use PlaceHolder\Interfaces\LoginInterface;

class LoginModel implements LoginInterface
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
    public function storeLoginAttempt($user_id, $login_result): bool
    {
        $query_string = $this->sql_queries->storeUserLoginLog();
        $query_params = [':UserID' => $user_id, ':LoginCompleted' => $login_result];

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
    public function getUserPassword($user_id, $username): string
    {
        $query_string = $this->sql_queries->getUserPassword();
        $query_params = [':UserID' => $user_id, ':Username' => $username];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == true)
        {
            return 'Unfortunately there was a login error. Please try again later.';
        }

        else
        {
            $result = $this->database_wrapper->safeFetchArray();
            return $result['UserPassword'];
        }

    }

    /**
     * @inheritDoc
     */
    public function getUserID($username): string
    {
        $query_string = $this->sql_queries->getUserID();
        $query_params = [':Username' => $username];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == false) //Query executed successfully
        {
            $returned_data = $this->database_wrapper->safeFetchArray();

            if($returned_data != false)
            {
                return $returned_data['UserID'];
            }

            else
            {
                return "User not found";
            }

        }

        else //Query returned an error
        {
            return "Query error";
        }
    }

    /**
     * @inheritDoc
     */
    public function getUserDetails($username): array
    {
        $query_string = $this->sql_queries->getUserDetails();
        $query_params = [':Username' => $username];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == true)
        {
            return ['Unfortunately there was a login error. Please try again later.'];
        }

        else
        {
            $result = $this->database_wrapper->safeFetchArray();
            return $result;
        }
    }
}