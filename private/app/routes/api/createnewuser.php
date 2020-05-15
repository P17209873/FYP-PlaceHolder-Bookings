<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/createnewuser', function (Request $request, Response $response) use ($app) {

    session_start();
    $monolog = $app->getContainer()->get('monologWrapper');

    $request_body = $request->getParsedBody();

//    return var_dump($request_body);

    if($request_body === null)
    {
        $response->getBody()->write(json_encode("Request body is empty"));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

//    $tainted_params = ['username' => $request_body['username'], 'email' => $request_body['email'], 'first-name' => $request_body['first-name'],
//                        'surname' => $request_body['surname'], 'psw' => $request_body['psw'], 'psw-repeat' => $request_body['psw-repeat']];

    //above commented out code shows params as used with Postman to test API, but register.js does not use those, hence
    //using 0, 1, 2, 3, etc
    $tainted_params = ['username' => $request_body[0], 'email' => $request_body[1], 'first-name' => $request_body[2],
        'surname' => $request_body[3], 'psw' => $request_body[4], 'psw-repeat' => $request_body[5]];

    $cleaned_params = cleanParams($app, $tainted_params);

    $username_exists_result = doesUsernameExist($app, $cleaned_params['sanitised_username']);

    if($username_exists_result === true){
        $response->getBody()->write(json_encode('Username already exists in database'));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(406);
    }

    $email_exists_result = doesEmailExist($app, $cleaned_params['sanitised_email']);

    if($email_exists_result === true){
        $response->getBody()->write(json_encode('Email address already exists in database'));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(406);
    }

    if($username_exists_result != true && $cleaned_params['psw'] === $cleaned_params['psw-repeat'] &&
        $email_exists_result != true && strpos($cleaned_params['sanitised_username'], " ") === false){

        // ensures that there are no nulls in the passed values
        $check_nulls = [];
        foreach ($cleaned_params as $key => $value) {
            if ($value != null) {
                $check_nulls[$key] = false;
            } else {
                $check_nulls[$key] = true;
            }
        }

        // IF THERE ARE NO TRUES IN CHECK NULLS ARRAY
        if (!(in_array(true, $check_nulls))) {
            $hashed_password = hashPassword($app, $cleaned_params['psw']);

            $cleaned_params['psw'] = ''; // clears the original password completely
            $cleaned_params['psw-repeat'] = ''; // clears the (repeated) original password completely

            $result = createNewUser($app, $cleaned_params, $hashed_password);

            if($result === 'User account was created successfully'){
                $_SESSION['message'] = 'User successfully created';
                $monolog->addLogMessage($cleaned_params['sanitised_username'] . ':' . $_SESSION['message'], 'info');
                $response->getBody()->write(json_encode($_SESSION['message']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
            }

            else {
                $_SESSION['error'] = 'User account was not created';
                $monolog->addLogMessage($cleaned_params['sanitised_username'] . ':' . $_SESSION['error'], 'info');
                $response->getBody()->write(json_encode($_SESSION['error']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

;        }
    } else {
        $_SESSION['error'] = 'Invalid account credentials';
        $monolog->addLogMessage($_SESSION['error'], 'info');
        $response->getBody()->write(json_encode($_SESSION['error']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

})->setName('createnewuser');

/**
 * @param $app
 * @param $tainted_params
 * @return array
 */
function cleanParams($app, $tainted_params)
{
    $cleaned_params = [];
    $validator = $app->getContainer()->get('validator');

    foreach ($tainted_params as $key => $param) {
        if ($key != 'psw' && $key != 'psw-repeat') {
            $cleaned_params['sanitised_' . $key] = $validator->sanitiseString($param);
        } else {
            $cleaned_params[$key] = $tainted_params[$key];
        }
    }

    return $cleaned_params;
}

/**
 * Hashes the password using BCrypt functions
 *
 * @param $app
 * @param $password_to_hash
 * @return string
 */
function hashPassword($app, $password_to_hash): string
{
    $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');
    $hashed_password = $bcrypt_wrapper->createHashedPassword($password_to_hash);
    return $hashed_password;
}

/**
 * Checks the database (using the function defined in the model) to check whether the entered username already
 * exists
 *
 * @param $app
 * @param $username
 * @return bool
 */
function doesUsernameExist($app, $username): bool
{
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $model = $app->getContainer()->get('registrationModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $model->doesUsernameExist($username);

}

/**
 * Checks the database (using the function defined in the model) to check whether the entered email address already
 * exists
 *
 * @param $app
 * @param $email
 * @return bool
 */
function doesEmailExist($app, $email): bool
{
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $model = $app->getContainer()->get('registrationModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $model->doesEmailExist($email);
}

/**
 * Creates a new user in the database by calling the relevant method in the RegistrationModel, which deals with
 * the process of executing the SQL query
 *
 * @param $app
 * @param $cleaned_params
 * @param $hashed_password
 */
function createNewUser($app, $cleaned_params, $hashed_password)
{
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $model = $app->getContainer()->get('registrationModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $cleaned_username = $cleaned_params['sanitised_username'];
    $cleaned_firstname = $cleaned_params['sanitised_first-name'];
    $cleaned_surname = $cleaned_params['sanitised_surname'];
    $cleaned_email = $cleaned_params['sanitised_email'];

    $verification = $model->createNewUser($cleaned_username, $cleaned_email, $cleaned_firstname,
                                          $cleaned_surname, $hashed_password);

    if($verification == true) {
        return 'User account was created successfully';
    }

    else {
        return 'There was an error creating the new user account';
    }
}
