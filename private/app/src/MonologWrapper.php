<?php


namespace PlaceHolder;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PlaceHolder\Interfaces\LoggerInterface;


class MonologWrapper implements LoggerInterface
{

    /**
     * @inheritDoc
     */
    public function setLogType($logType): int
    {
        switch ($logType){
            case 'debug':
                return Logger::DEBUG;
                break;
            case 'info':
                return Logger::INFO;
                break;
            case 'warning':
                return Logger::WARNING;
                break;
            case 'error':
                return Logger::ERROR;
                break;
            case 'critical':
                return Logger::CRITICAL;
                break;
            case 'alert':
                return Logger::ALERT;
                break;
            case 'emergency':
                return Logger::EMERGENCY;
                break;
        }
    }

    /**
     * @inheritDoc
     */
    public function addLogMessage($message, $logType): void
    {
        $logger = new Logger('PlaceHolderLogger');
        $logger->pushHandler(new StreamHandler(LOG_FILE_LOCATION . LOG_FILE_NAME, $this->setLogType($logType)));
        $logger->$logType($message);
    }
}