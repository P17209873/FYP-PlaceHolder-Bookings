<?php


namespace PlaceHolder;


class DatabaseWrapper
{
    private $database_connection_settings;
    private $db_handle;
    private $errors;
    private $prepared_statement;

    public function __construct()
    {
        $this->database_connection_settings = null;
        $this->db_handle = null;
        $this->prepared_statement = null;
        $this->errors = [];
    }

    public function __destruct(){}

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function makeDatabaseConnection()
    {
        $pdo = false;
        $pdo_error = '';

        $database_settings = $this->database_connection_settings;

        $host_name = $database_settings['rdbms'] . ':host=' . $database_settings['host'];
        $port_number = ';port=' . $database_settings['port'];
        $user_database = ';dbname=' . $database_settings['db_name'];
        $host_details = $host_name . $port_number . $user_database;
        $user_name = $database_settings['user_name'];
        $user_password = $database_settings['user_password'];
        $pdo_attributes = $database_settings['options'];

        try {
            $pdo_handle = new \PDO($host_details, $user_name, $user_password, $pdo_attributes);
            $this->db_handle = $pdo_handle;
        } catch (\PDOException $exception_object) {
            trigger_error('error connecting to database');
            $pdo_error = 'error connecting to database';
        }

        return $pdo_error;

    }

    public function safeQuery($query_string, $params=null)
    {
        $this->errors['db_error'] = false;
        $query_parameters = $params;

        try {
            $this->prepared_statement = $this->db_handle->prepare($query_string);
            $execute_result = $this->prepared_statement->execute($query_parameters);
            $this->errors['execute-OK'] = $execute_result;
        } catch (PDOException $exception_object) {
            $error_message  = 'PDO Exception caught. ';
            $error_message .= 'Error with the database access.' . "\n";
            $error_message .= 'SQL query: ' . $query_string . "\n";
            $error_message .= 'Error: ' . var_dump($this->prepared_statement->errorInfo(), true) . "\n";
            // NB would usually log to file for sysadmin attention
            $this->errors['db_error'] = true;
            $this->errors['sql_error'] = $error_message;
        }
        return $this->errors['db_error'];
    }

    public function countRows()
    {
        $num_rows = $this->prepared_statement->rowCount();
        return $num_rows;
    }

    public function safeFetchRow()
    {
        $record_set = $this->prepared_statement->fetch(\PDO::FETCH_NUM);
        return $record_set;
    }

    public function safeFetchArray()
    {
        $row = $this->prepared_statement->fetch(\PDO::FETCH_ASSOC);
        $this->prepared_statement->closeCursor();
        return $row;
    }

    public function safeFetchAll()
    {
        $result_set = $this->prepared_statement->fetchAll();
        $this->prepared_statement->closeCursor();
        return $result_set;
    }

    public function lastInsertedID()
    {
        $sql_query = 'SELECT LAST_INSERT_ID()';

        $this->safeQuery($sql_query);
        $last_inserted_id = $this->safeFetchArray();
        $last_inserted_id = $last_inserted_id['LAST_INSERT_ID()'];
        return $last_inserted_id;
    }

}