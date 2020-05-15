<?php

namespace PlaceHolder;

use PHPUnit\Framework\TestCase;

class DatabaseWrapperTest extends TestCase
{
    public function testValidMakeDatabaseConnection()
    {
        $database_wrapper = new \PlaceHolder\DatabaseWrapper();

        $pdo_settings = [
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

        $fake_settings = [
            'rdbms' => 'postgres',
            'host' => 'localhost',
            'db_name' => 'PlaceHolderFakeDb',
            'port' => '3306',
            'user_name' => 'FakePlaceHolderUser',
            'user_password' => 'FakePassword',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => true,
            ],
        ];

        $database_wrapper->setDatabaseConnectionSettings($fake_settings);
        $this->expectError();
        $this->assertContains('error connecting to database', $database_wrapper->makeDatabaseConnection(), 'error connecting to database');

        $database_wrapper->setDatabaseConnectionSettings($pdo_settings);

        $this->assertEquals(false, $database_wrapper->makeDatabaseConnection());
    }

    public function testValidSafeQueryRuns(){
        $database_wrapper = new \PlaceHolder\DatabaseWrapper();

        $pdo_settings = [
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

        $database_wrapper->setDatabaseConnectionSettings($pdo_settings);
        $connection = $database_wrapper->makeDatabaseConnection();
        $query = $database_wrapper->safeQuery('SELECT * FROM Country');
        $result = $database_wrapper->safeFetchRow();

        $this->assertFalse($result == null);
        $this->assertFalse($query == true);
    }

    public function testCountRows(){
        $database_wrapper = new \PlaceHolder\DatabaseWrapper();

        $pdo_settings = [
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

        $database_wrapper->setDatabaseConnectionSettings($pdo_settings);
        $connection = $database_wrapper->makeDatabaseConnection();
        $query = $database_wrapper->safeQuery('SELECT * FROM Country');
        $rows = $database_wrapper->countRows();


        $this->assertFalse($rows == null);
    }


}