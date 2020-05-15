<?php
/**
 * LoggerInterface.php
 *
 * Defines an interface for all classes related to logging data in the application.
 *
 * @author Matthew Trim <p17209873@my365.dmu.ac.uk>
 */

namespace PlaceHolder\Interfaces;


interface LoggerInterface
{
    /**
     * Allows the log type to be set externally, and passed through into the application
     *
     * @param $logType
     * @return int - returns selected log type in Logger format
     */
    public function setLogType($logType) : int;

    /**
     * Adds the log message to the log file
     *
     * @param $message
     * @param $logType
     */
    public function addLogMessage($message, $logType) : void;
}