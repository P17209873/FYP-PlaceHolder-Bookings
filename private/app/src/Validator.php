<?php
/**
 * Validator.php
 *
 * Class is used to validate the data that has been passed through from the client side
 */
namespace PlaceHolder;

/**
 * Validates any input
 *
 * Class Validator
 * @package PlaceHolder
 */
class Validator
{
    public function __construct(){}

    public function __destruct(){}

    /**
     * Filters and sanitizes any string data
     * @param string $string_to_sanitise
     * @return string
     */
    public function sanitiseString(string $string_to_sanitise): string
    {
        $sanitised_string = false;

        if (!empty($string_to_sanitise)) {
            $sanitised_string = filter_var($string_to_sanitise, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return $sanitised_string;
    }

    /**
     * Validates the registration details
     * @param $tainted_userinput
     * @return bool
     */
    public function validateRegistrationDetails($tainted_userinput): bool
    {
        $valid = false;

        $username_valid = false;
        $email_valid = false;
        $firstname_valid = false;
        $lastname_valid = false;

        if(isset($tainted_userinput['username'])){
            if($tainted_userinput['username'] ){
                $username_valid = true; // Figure out how to do length calculations on array value
            }
        }

        if(isset($tainted_userinput['email'])){
            if(filter_var($tainted_userinput['email'], FILTER_VALIDATE_EMAIL)) {
                $email_valid = true;
            }
        }

        if(isset($tainted_userinput['firstname'])){
            if(preg_match("/^[a-zA-Z ]*$/", $tainted_userinput['firstname'])) {
                $firstname_valid = true;
            }
        }

        if(isset($tainted_userinput['lastname'])){
            if(preg_match("/^[a-zA-Z ]*$/", $tainted_userinput['lastname'])) {
                $lastname_valid = true;
            }
        }

        if ($username_valid && $email_valid && $firstname_valid && $lastname_valid) {
            $valid = true;
        }
        return $valid;
    }

    function validateEventDetails($tainted_input, $event_types): bool
    {
        $valid = false;

        $title_valid = false;
        $description_valid = false;
        $type_valid = false;
        $start_time_valid = false;
        $end_time_valid = false;

        if(isset($tainted_input[0])) { //event title
            if(strlen($tainted_input[0]) > 2 && strlen($tainted_input[0]) < 21 ){ //allows for lengths between 3 and 20
                $title_valid = true;
            }
        }

        if(isset($tainted_input[1])) {
            if(strlen($tainted_input[1]) < 500) {
                $description_valid = true;
            }

        }

        if(isset($tainted_input[2])) { //event type
            if(in_array($tainted_input[2], $event_types)) {
                $type_valid = true;
            }
        }

        if(isset($tainted_input[3]) && isset($tainted_input[4])) {
            $start = strtotime($tainted_input[3]);
            $end = strtotime($tainted_input[4]);

            if($end > $start) {
                $start_time_valid = true;
                $end_time_valid = true;
            }

        }

        if($title_valid && $description_valid && $type_valid && $start_time_valid && $end_time_valid){
            $valid = true;
        }

        return $valid;
    }

}
