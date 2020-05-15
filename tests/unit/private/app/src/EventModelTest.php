<?php

namespace PlaceHolder;

use PHPUnit\Framework\TestCase;

class EventModelTest extends TestCase
{
    private EventModel $model;
    private array $pdo_settings;
    private SQLQueries $sql_queries;
    private DatabaseWrapper $db_wrapper;
    private BcryptWrapper $bcrypt;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->bcrypt = new BcryptWrapper();

        $this->model = new EventModel();
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

    public function testCreateEvent()
    {
        $this->model->setSqlQueries($this->sql_queries);
        $this->model->setDatabaseWrapper($this->db_wrapper);
        $this->model->setDatabaseConnectionSettings($this->pdo_settings);

        $result = $this->model->createEvent([ "TestEvent", "This is a test description", 2, "2020-11-11 04:30:00", "2020-11-11 15:30:00", 1 ]);

        $this->assertEquals(true, $result);
    }

    public function testAmendEvent()
    {
        $this->model->setSqlQueries($this->sql_queries);
        $this->model->setDatabaseWrapper($this->db_wrapper);
        $this->model->setDatabaseConnectionSettings($this->pdo_settings);

        $result = $this->model->updateEvent(3, [ "TestUpdateName", "Test updated description", "3", "2020-12-12 05:40:00", "2020-12-12 15:45:00" ]);

        $this->assertEquals(true, $result);
    }

}
