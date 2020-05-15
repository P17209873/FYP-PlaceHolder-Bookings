<?php

namespace PlaceHolder;

use PHPUnit\Framework\TestCase;

class RegistrationModelTest extends TestCase
{
    private RegistrationModel $model;
    private array $pdo_settings;
    private SQLQueries $sql_queries;
    private DatabaseWrapper $db_wrapper;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        //below does not use dependency injection, could be worth implementing?
        $this->model = new RegistrationModel();
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

    public function testValidCreateNewUser()
    {
        $this->model->setSqlQueries($this->sql_queries);
        $this->model->setDatabaseWrapper($this->db_wrapper);
        $this->model->setDatabaseConnectionSettings($this->pdo_settings);

        $this->assertEquals(true, $this->model->createNewUser('fakeUsername',
            'fakedEmail@email.com', 'fakeFirstName', 'fakeLastName',
            'fakeHashedPassword'));


    }

    public function testValidDoesUserNameExist()
    {
        $this->model->setSqlQueries($this->sql_queries);
        $this->model->setDatabaseWrapper($this->db_wrapper);
        $this->model->setDatabaseConnectionSettings($this->pdo_settings);

        $this->assertEquals(true, $this->model->doesUsernameExist('fakeUsername'));
        $this->assertEquals(false, $this->model->doesUsernameExist('fakeUsernameNew'));

    } 

    public function testValidDoesEmailExist()
    {
        $this->model->setSqlQueries($this->sql_queries);
        $this->model->setDatabaseWrapper($this->db_wrapper);
        $this->model->setDatabaseConnectionSettings($this->pdo_settings);

        $this->assertEquals(true, $this->model->doesEmailExist('fakedEmail@email.com'));
        $this->assertEquals(false, $this->model->doesEmailExist('fakeEmailNew@email.com'));
    }


}
