<?php


namespace PlaceHolder;

use PlaceHolder\Interfaces\EventInterface;


class EventModel implements EventInterface
{
    private $database_wrapper;
    private $database_connection_settings;
    private $sql_queries;

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
    public function getEventTypes(): array
    {
        $error = [];

        $query_string = $this->sql_queries->getEventTypes();

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string);

        if($result == false) //Query executed successfully
        {
            $returned_data = $this->database_wrapper->safeFetchAll();

            if($returned_data != false)
            {
                $temp = [];

                foreach($returned_data as $type) {
                    foreach ($type as $type_name){
                        array_push($temp, $type_name);
                    }
                }

                $returned_data = $temp;
                return $returned_data;
            }

            else
            {
                $error[0] = "Query error";
                return $error;
            }

        }

        else //Query returned an error
        {
            $error[0] = "Query error";
            return $error;
        }
    }

    public function createEvent(array $event): bool
    {
        $query_string = $this->sql_queries->createEvent();
        $query_params = [':EventName' => $event[0], ':EventDescription' => $event[1], ':EventTypeID' => $event[2],
                         ':TimeStart' => $event[3], ':TimeEnd' => $event[4], ':CreatedByUserID'=> $event[5]];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result !== false) {
            return false;
        }

        else
        {
//            return 'Event created.';
            return true;
        }
    }

    public function getAllEvents(): array
    {
        $error = [];

        $query_string = $this->sql_queries->getAllEvents();

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query = $this->database_wrapper->safeQuery($query_string);
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

    public function getEvent($event_id): array
    {
        $error = [];

        $query_string = $this->sql_queries->getEvent();
        $query_params = ['EventID' => $event_id];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query = $this->database_wrapper->safeQuery($query_string, $query_params);
        $result = $this->database_wrapper->safeFetchArray();

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

    public function getEventTypeId($event_type) {

        $error = [];

        $query_string = $this->sql_queries->getEventTypeId();
        $query_params = [':EventType' => $event_type];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query = $this->database_wrapper->safeQuery($query_string, $query_params);
        $result = $this->database_wrapper->safeFetchArray();

        if($result == false)
        {
            $error[0] = true;
            return $error;
        }

        else
        {
            return $result['EventTypeID'];
        }

    }

    public function getEventId($event_details) {

        $error = [];

        $query_string = $this->sql_queries->getEventId();
        $query_params = [':EventName' => $event_details[0], ':EventDescription' => $event_details[1],
            ':TimeStart' => $event_details[3], ':TimeEnd' => $event_details[4], ':EventTypeID' => $event_details[2]];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query = $this->database_wrapper->safeQuery($query_string, $query_params);
        $result = $this->database_wrapper->safeFetchArray();

        if($result == false)
        {
            $error[0] = true;
            return $error;
        }

        else
        {
            return $result['EventID'];
        }

    }

    public function getUserCreatedID($event_id) {

        $error = [];

        $query_string = $this->sql_queries->getUserCreatedID();
        $query_params = [':EventID' => $event_id];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query = $this->database_wrapper->safeQuery($query_string, $query_params);
        $result = $this->database_wrapper->safeFetchArray();

        if($result == false)
        {
            $error[0] = true;
            return $error;
        }

        else
        {
            return $result['CreatedByUserID'];
        }

    }

    public function updateEvent($event_id, $new_event_details) {

        $query_string = $this->sql_queries->updateEvent();
        $query_params = [':EventID' => $event_id, ':EventName' => $new_event_details[0],
                         ':EventDescription' => $new_event_details[1], ':EventTypeID' => $new_event_details[2],
                         ':TimeStart' => $new_event_details[3], ':TimeEnd' => $new_event_details[4]];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query = $this->database_wrapper->safeQuery($query_string, $query_params);

        if(!isset($query['db_error'])){
            return true;
        }

        else {
            return false;
        }
    }

    public function deleteEvent($event_id) {

        $query_string = $this->sql_queries->deleteEvent();
        $query_params = [':EventID' => $event_id];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query = $this->database_wrapper->safeQuery($query_string, $query_params);

    }

    public function createBooking($event_id, $user_id) {

        $query_string = $this->sql_queries->createBooking();
        $query_params = [':EventID' => $event_id, ':UserID' => $user_id];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $this->database_wrapper->safeQuery($query_string, $query_params);

    }

    public function getEventRegistrationResult($event_id, $user_id) {

        $query_string = $this->sql_queries->getEventRegistrationResult();
        $query_params = [':EventID' => $event_id, ':UserID' => $user_id];

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query = $this->database_wrapper->safeQuery($query_string, $query_params);
        $result = $this->database_wrapper->safeFetchArray();

        return ($this->database_wrapper->countRows() > 0);

    }

    public function getEventsHomepagePreview()
    {

        $query_string = $this->sql_queries->getRecentEvents();

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query = $this->database_wrapper->safeQuery($query_string);
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

    public function getEventCount()
    {
        $query_string = "SELECT count(1) FROM Events";

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $this->database_wrapper->safeQuery($query_string);
        return $this->database_wrapper->safeFetchAll();
    }

}