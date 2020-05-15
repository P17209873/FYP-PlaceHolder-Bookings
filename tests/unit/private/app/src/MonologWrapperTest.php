<?php

namespace PlaceHolder;

use PHPUnit\Framework\TestCase;

class MonologWrapperTest extends TestCase
{
    public function testSetDebug() {
        $monolog = new MonologWrapper();

        $this->assertEquals(true, $monolog->setLogType('debug'));
    }

    public function testSetInfo() {
        $monolog = new MonologWrapper();

        $this->assertEquals(true, $monolog->setLogType('info'));
    }

    public function testSetWarning() {
        $monolog = new MonologWrapper();

        $this->assertEquals(true, $monolog->setLogType('warning'));
    }

    public function testSetError() {
        $monolog = new MonologWrapper();

        $this->assertEquals(true, $monolog->setLogType('error'));
    }

    public function testSetCritical() {
        $monolog = new MonologWrapper();

        $this->assertEquals(true, $monolog->setLogType('critical'));
    }

    public function testSetAlert() {
        $monolog = new MonologWrapper();

        $this->assertEquals(true, $monolog->setLogType('alert'));
    }

    public function testSetEmergency() {
        $monolog = new MonologWrapper();

        $this->assertEquals(true, $monolog->setLogType('emergency'));
    }

    public function testAddLogMessage() {
        define('LOG_FILE_LOCATION', '../logs/');
        define('LOG_FILE_NAME', 'PlaceHolder.log');

        $monolog = new MonologWrapper();

        $this->assertEquals(false, $monolog->addLogMessage('test debug message', 'debug'));

    }
}