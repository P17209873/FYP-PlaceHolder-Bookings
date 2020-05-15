<?php


namespace PlaceHolder\Interfaces;


interface BookingInterface
{
    public function setDatabaseWrapper($database_wrapper): void;

    public function setDatabaseConnectionSettings($database_connection_settings): void;

    public function setSqlQueries($sql_queries): void;

    public function createBooking(int $user_id, int $event_id, int $location_id): bool;

    public function getUserBookings(int $user_id): array;

    public function getAllBookings(): array;

    public function getBooking($booking_id): array;
}