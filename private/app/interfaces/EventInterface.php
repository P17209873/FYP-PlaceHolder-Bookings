<?php
/**
 * EventInterface.php
 *
 * Defines an interface for all classes related to logging data in the application.
 *
 * @author Matthew Trim <p17209873@my365.dmu.ac.uk>
 */


namespace PlaceHolder\Interfaces;


interface EventInterface
{
    public function setDatabaseWrapper($database_wrapper): void;

    public function setDatabaseConnectionSettings($database_connection_settings): void;

    public function setSqlQueries($sql_queries): void;

    public function getEventTypes(): array;

    public function createEvent(array $event): bool;

    public function getAllEvents(): array;

    public function getEvent($event_id): array;
}