<?php


namespace PlaceHolder;


use PlaceHolder\Interfaces\UserInterface;

class UserModel implements UserInterface
{
    private $database_wrapper;
    private $database_connection_settings;
    private $sql_queries;

    private int $user_type;
    private string $username;
    private string $user_first_name;
    private string $user_last_name;
    private int $user_id;

    /**
     * User constructor.
     * @param $user_id
     * @param $username
     * @param $user_first_name
     * @param $user_last_name
     * @param string $user_type
     */
    //UserType: 1 = Guest
    //UserType: 2 = Normal
    //UserType: 3 = Admin
    public function __construct($user_id, $username, $user_first_name, $user_last_name, $user_type="2") //
    {
        $this->username = $username;
        $this->user_first_name = $user_first_name;
        $this->user_last_name = $user_last_name;
        $this->user_type = $user_type;
        $this->user_id = $user_id;
    }

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


    public function setUserType($user_type): void
    {
        $this->user_type = $user_type;
    }

    public function setUserFirstName($user_first_name): void
    {
        $this->user_first_name = $user_first_name;
    }

    public function setUserLastName($user_last_name): void
    {
        $this->user_type = $user_last_name;
    }

    public function getUserType(): string
    {
        switch($this->user_type) {
            case 1:
                return "Guest";
                break;
            case 2:
                return "Normal";
                break;
            case 3:
                return "Admin";
                break;
            default:
                return "Invalid user type";
                break;
        }
    }

    public function getIsAdmin() {
        if($this->user_type === "Admin" || $this->user_type === 3) {
            return true;
        }

        else {
            return false;
        }
    }

    public function getUserID(): int
    {
        return $this->user_id;
    }

    public function getUserName(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->user_first_name;
    }

    public function getLastName(): string
    {
        return $this->user_last_name;
    }

    public function getFullName(): string
    {
        return $this->user_first_name . ' ' . $this->user_last_name;
    }

    public function getUserEmail()
    {
        // TODO: Retrieve user email from database
        // (store email address in object?)
    }

    public function updateDatabase()
    {
        // TODO: Update user changes in database
    }

    public function getBookedEvents($user_id)
    {
        $error = [];
        $query_string = $this->sql_queries->getUserBookedEvents();
        $query_params = [':UserID' => $user_id];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query = $this->database_wrapper->safeQuery($query_string, $query_params);
        $result = $this->database_wrapper->safeFetchAll();

        if($result == false)
        {
            $error[0] = true;
            return $error;
        }

        else
        {
            return $result;
        }
    }

    public function getCreatedEvents($user_id)
    {

        $error = [];
        $query_string = $this->sql_queries->getAllUserCreatedEvents();
        $query_params = [':UserID' => $user_id];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query = $this->database_wrapper->safeQuery($query_string, $query_params);
        $result = $this->database_wrapper->safeFetchAll();

        if($result == false)
        {
            $error[0] = true;
            return $error;
        }

        else
        {
            return $result;
        }

    }

    public function updatePassword($user_id, $new_password)
    {
        $query_string = $this->sql_queries->setUserPassword();
        $query_params = [':UserID' => $user_id, ':NewPassword' => $new_password];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $this->database_wrapper->safeQuery($query_string, $query_params);
    }

    public function adminGetAllUsers()
    {
        $query_string = $this->sql_queries->adminGetAllUsers();

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $this->database_wrapper->safeQuery($query_string);

        return $this->database_wrapper->safeFetchAll();
    }

    public function getUserCount()
    {
        $query_string = "SELECT count(1) FROM Users";

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $this->database_wrapper->safeQuery($query_string);
        return $this->database_wrapper->safeFetchRow();
    }

    // As PDO cannot be serialized to the session superglobal, PDO-related objects need to be cleared completely
    public function clearDatabaseSettings() {
        $this->database_connection_settings = null;
        $this->database_wrapper = null;
        $this->sql_queries = null;
    }
}