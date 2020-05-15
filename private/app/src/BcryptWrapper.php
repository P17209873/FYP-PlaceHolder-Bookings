<?php
/**
 * BcryptWrapper.php
 *
 * Bcrypt Wrapper class to make using bcrypt inbuilt functions easier. (thanks cfi!)
 */

namespace PlaceHolder;

/**
 * Wrapper class used to perform bcrypt functions
 *
 * Class BcryptWrapper
 * @package SecureWebAppCoursework
 */
class BcryptWrapper
{

    public function __construct(){}

    public function __destruct(){}

    /**
     * Wraps the password_hash method in an easy to use function that returns a hashed password
     *
     * @param $string_to_hash
     * @return bool|string
     */
    public function createHashedPassword($string_to_hash)
    {
        $password_to_hash = $string_to_hash;
        $bcrypt_hashed_password = '';

        if (!empty($password_to_hash)) {
            $options = ['cost' => BCRYPT_COST];
            $bcrypt_hashed_password = password_hash($password_to_hash, BCRYPT_ALGO, $options);
        }
        return $bcrypt_hashed_password;
    }

    /**
     * Wraps the password_verify function in an easy to use function that verifies that the stored user password hash with
     * the new password entered by the user
     *
     * @param $string_to_check
     * @param $stored_user_password_hash
     * @return bool
     */
    public function authenticatePassword($string_to_check, $stored_user_password_hash)
    {
        $user_authenticated = false;
        $current_user_password = $string_to_check;
        $stored_user_password_hash = $stored_user_password_hash;
        if (!empty($current_user_password) && !empty($stored_user_password_hash)) {
            if (password_verify($current_user_password, $stored_user_password_hash)) {
                $user_authenticated = true;
            }
        }
        return $user_authenticated;
    }
}
