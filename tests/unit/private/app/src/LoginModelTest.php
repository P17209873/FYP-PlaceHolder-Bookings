<?php

namespace PlaceHolder;

use PHPUnit\Framework\TestCase;

class LoginModelTest extends TestCase
{
    private LoginModel $model;
    private array $pdo_settings;
    private SQLQueries $sql_queries;
    private DatabaseWrapper $db_wrapper;
    private BcryptWrapper $bcrypt;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->bcrypt = new BcryptWrapper();

        $this->model = new LoginModel();
        $this->pdo_settings = [
            'rdbms' => 'mysql',
            'host' => 'localhost',
            'db_name' => 'PlaceHolder',
            'port' => '3306',
            'user_name' => 'PlaceHolderUser',
            'user_password' => 'ubG4hofF',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => true,
            ],
        ];
        $this->sql_queries = new SQLQueries();
        $this->db_wrapper = new DatabaseWrapper();
    }

    public function testStoreLoginAttempt()
    {
        $this->model->setSqlQueries($this->sql_queries);
        $this->model->setDatabaseWrapper($this->db_wrapper);
        $this->model->setDatabaseConnectionSettings($this->pdo_settings);

        $this->assertEquals(true, $this->model->storeLoginAttempt(1, 1));
    }

    public function testLogin()
    {
        $this->model->setSqlQueries($this->sql_queries);
        $this->model->setDatabaseWrapper($this->db_wrapper);
        $this->model->setDatabaseConnectionSettings($this->pdo_settings);

        $result = null;

        /*
         * Check to see user ID exists
         * Check to see whether user password is correct
         * result equals true / false
         */

        $test_username = "Matt";
        $test_user_id = $this->model->getUserID($test_username);
        $err = "User not found";

        if($test_user_id !== $err)
        {
            $password = $this->model->getUserPassword($test_user_id, $test_username);

            $result = $this->bcrypt->authenticatePassword("password", $password);

        }

        $this->assertEquals(true, $result);

        $test_username = "Matt";
        $test_user_id = $this->model->getUserID($test_username);
        $err = "User not found";

        if($test_user_id !== $err)
        {
            $password = $this->model->getUserPassword($test_user_id, $test_username);

            $result = $this->bcrypt->authenticatePassword("wrong_password", $password);

        }

        $this->assertEquals(false, $result);

    }

}
