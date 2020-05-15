<?php
/**
 * UserInterface.php
 *
 * Defines an interface for all classes related to storing user details in the application.
 *
 * @author Matthew Trim <p17209873@my365.dmu.ac.uk>
 */

namespace PlaceHolder\Interfaces;

/**
 * Interface UserInterface
 * @package PlaceHolder
 */
interface UserInterface
{

    public function setUserType($user_type) : void;

    public function setUserFirstName($user_first_name) : void;

    public function setUserLastName($user_last_name) : void;

    public function getUserType(): string;

    public function getUserName(): string;

    public function getFirstName(): string;

    public function getLastName(): string;

    public function getFullName(): string;



}