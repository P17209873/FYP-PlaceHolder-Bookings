<?php
/**
 * SQLQueries.php
 *
 * Contains the SQL Queries that are necessary for performing CRUD tasks in the application.
 *
 * @author Matthew Trim <p17209873@my365.dmu.ac.uk>
 */

namespace PlaceHolder;

/**
 * Defines all the SQL queries that the application depends on to run
 *
 * Class SQLQueries
 * @package PlaceHolder-Bookings
 */
class SQLQueries {


    /**
     * This query adds new users to the database from the registration form.
     *
     * @return string <- returns necessary query string
     */
    public function createNewUser()
    {
        $query_string = "INSERT INTO Users ";
        $query_string .= "SET ";
        $query_string .= "Username = :Username, ";
        $query_string .= "UserPassword = :UserPassword, ";
        $query_string .= "UserEmail = :UserEmail, ";
        $query_string .= "UserFirstName = :UserFirstName, ";
        $query_string .= "UserLastName = :UserLastName, ";
        $query_string .= "UserTypeID = :UserTypeID";

        return $query_string;
    }

    /**
     * This query checks the database to see whether a user already exists. It is used by the login route to check that
     * the entered username exists, and by the registration route to prevent multiple users from having the same
     * username.
     *
     * @return string
     */
    public function getUserID()
    {
        $query_string = "SELECT UserID FROM Users ";
        $query_string .= "WHERE Username = :Username";

        return $query_string;
    }

    /**
     * This query checks the database to see whether a user entered email address already exists. It is used by the
     * login route to check that the entered username exists, and by the registration route to prevent multiple users
     * from having the same username.
     *
     * @return string
     */
    public function getUserEmail()
    {
        $query_string = "SELECT UserID FROM Users ";
        $query_string .= "WHERE UserEmail = :UserEmail";

        return $query_string;
    }

    /**
     * This query inserts login attempts into the UserLoginLogs table, taking the UserID and LoginCompleted values to
     * store every attempt, and whether the attempt succeeded or failed.
     *
     * @return string
     */
    public function storeUserLoginLog()
    {
        $query_string  = "INSERT INTO UserLoginLogs ";
        $query_string .= "SET ";
        $query_string .= "UserID = :UserID, ";
        $query_string .= "LoginCompleted = :LoginCompleted";
        return $query_string;
    }

    /**
     * Selects the user id, username, and user password from the Users table for the relevant User ID and Username.
     *
     * @return string
     */
    public function getUserPassword()
    {
        $query_string = "SELECT UserID, Username, UserPassword ";
        $query_string .= "FROM Users ";
        $query_string .= "WHERE ";
        $query_string .= "UserID = :UserID AND ";
        $query_string .= "Username = :Username";

        return $query_string;
    }

    public function setUserPassword()
    {
        $query_string = "UPDATE Users SET ";
        $query_string .= "UserPassword = :NewPassword ";
        $query_string .= "WHERE UserID = :UserID";

        return $query_string;
    }

    /**
     * Returns the user information for a user, used when creating User object on user login.
     *
     * @return string
     */
    public function getUserDetails()
    {
        $query_string  = "SELECT UserID, Username, UserFirstName, UserLastName, UserTypeID ";
        $query_string .= "FROM Users ";
        $query_string .= "WHERE Username = :Username";

        return $query_string;
    }

    /**
     * Returns all event types.
     *
     * @return string
     */
    public function getEventTypes()
    {
        $query_string  = "SELECT EventType FROM EventType";
        return $query_string;
    }

    /**
     * Returns Event Type ID for a specific Event Type
     *
     * @return string
     */
    public function getEventTypeId()
    {
        $query_string = "SELECT EventTypeID FROM EventType ";
        $query_string .= "WHERE EventType = :EventType";

        return $query_string;
    }

    public function createEvent()
    {
        $query_string = "INSERT INTO Events SET ";
        $query_string .= "EventName = :EventName, ";
        $query_string .= "EventDescription = :EventDescription, ";
        $query_string .= "TimeStart = :TimeStart, ";
        $query_string .= "TimeEnd = :TimeEnd, ";
        $query_string .= "EventTypeID = :EventTypeID, ";
        $query_string .= "CreatedByUserID = :CreatedByUserID";

        return $query_string;
    }

    public function updateEvent()
    {
        $query_string = "UPDATE Events SET ";
        $query_string .= "EventName = :EventName, ";
        $query_string .= "EventDescription = :EventDescription, ";
        $query_string .= "TimeStart = :TimeStart, ";
        $query_string .= "TimeEnd = :TimeEnd, ";
        $query_string .= "EventTypeID = :EventTypeID ";
        $query_string .= "WHERE EventID = :EventID";

        return $query_string;
    }

    public function deleteEvent()
    {
        $query_string = "DELETE FROM Events WHERE ";
        $query_string .= "EventID = :EventID";

        return $query_string;
    }

    public function getEvent()
    {
        $query_string = "SELECT Events.EventID, Events.EventName, Events.EventDescription, ";
        $query_string .= "Events.TimeStart, Events.TimeEnd, Events.TimeCreated, Events.TimeLastUpdated, EventType.EventType, ";
        $query_string .= "Users.Username ";
        $query_string .= "FROM Events, EventType, Users ";
        $query_string .= "WHERE Users.UserID = Events.CreatedByUserID ";
        $query_string .= "AND Events.EventTypeID = EventType.EventTypeID ";
        $query_string .= "AND Events.EventID = :EventID";

        return $query_string;
    }

    public function getEventId()
    {
        $query_string = "SELECT EventID FROM Events ";
        $query_string .= "WHERE EventName = :EventName ";
        $query_string .= "AND EventDescription = :EventDescription ";
        $query_string .= "AND EventTypeID = :EventTypeID ";
        $query_string .= "AND TimeStart = :TimeStart ";
        $query_string .= "AND TimeEnd = :TimeEnd";

        return $query_string;
    }

    public function getAllEvents()
    {
        $query_string = "SELECT Events.EventID, Events.EventName, Events.EventDescription, ";
        $query_string .= "Events.TimeStart, Events.TimeEnd, Events.TimeCreated ,EventType.EventType, Users.Username ";
        $query_string .= "FROM Events, EventType, Users ";
        $query_string .= "WHERE Users.UserID = Events.CreatedByUserID ";
        $query_string .= "AND Events.EventTypeID = EventType.EventTypeID";

        return $query_string;
    }

    public function getAllUserCreatedEvents()
    {
        $query_string = "SELECT EventID, EventName, EventDescription, TimeStart, TimeEnd ";
        $query_string .= "FROM Events ";
        $query_string .= "WHERE CreatedByUserID = :UserID";

        return $query_string;
    }


    public function getUserCreatedID()
    {
        $query_string = "SELECT CreatedByUserID FROM Events ";
        $query_string .= "WHERE EventID = :EventID";

        return $query_string;
    }

    public function createBooking()
    {
        $query_string = "INSERT INTO Bookings SET ";
        $query_string .= "EventID = :EventID, ";
        $query_string .= "UserID = :UserID";

        return $query_string;
    }

    public function getEventRegistrationResult()
    {
        $query_string = "SELECT BookingID, DateBooked FROM Bookings ";
        $query_string .= "WHERE UserID = :UserID AND EventID = :EventID";

        return $query_string;
    }

    public function getUserBookedEvents()
    {
        $query_string = "SELECT Events.EventID, Events.EventName, Events.EventDescription, Events.TimeStart, Events.TimeEnd ";
        $query_string .= "FROM Events, Bookings, Users ";
        $query_string .= "WHERE Bookings.UserID = Users.UserID ";
        $query_string .= "AND Bookings.EventID = Events.EventID ";
        $query_string .= "AND Bookings.UserID = :UserID";

        return $query_string;
    }

    public function getRecentEvents()
    {
        $query_string = "SELECT EventID, EventName, EventDescription, TimeStart, TimeEnd ";
        $query_string .= "FROM Events ORDER BY EventID DESC LIMIT 2";

        return $query_string;
    }

    public function adminGetAllUsers()
    {
        $query_string = "SELECT UserID, Username, UserEmail, UserFirstName, UserLastName, UserCreationTimestamp ";
        $query_string .= "FROM Users";

        return $query_string;
    }

}
/* Select cities and countries:
 * select City.CityID, City.CityName, Country.CountryName from City, Country WHERE City.CountryID = Country.CountryID;
 */