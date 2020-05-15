<?php

use PlaceHolder\UserModel;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/login', function (Request $request, Response $response) use ($app) {

    session_start();
    $monolog = $app->getContainer()->get('monologWrapper');
    $bcrypt = $app->getContainer()->get('bcryptWrapper');

    $tainted_params = $request->getParsedBody();
    $cleaned_params = cleanParams($app, $tainted_params);

    $user_id_result = getUserID($app, $cleaned_params['sanitised_username']);

    if($user_id_result == "User not found" || $user_id_result == "Query error")
    {
        $monolog->addLogMessage('There was an issue with a user\'s request: ' . $user_id_result, 'info');

        $response->getBody()->write(json_encode("There was an issue with your request."));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);;
    }

    else //valid user ID found
    {
        $check_user_password = getUserPassword($app, $user_id_result, $cleaned_params["sanitised_username"]);
        $user_authenticated_result = $bcrypt->authenticatePassword($cleaned_params['sanitised_password'], $check_user_password);

        switch($user_authenticated_result) {
            case true:
                $user_authenticated_result=1;
                $_SESSION['username'] = $cleaned_params['sanitised_username'];
                $monolog->addLogMessage($_SESSION['username'] . ' logged in.', 'info');
                $user_details = getUserDetails($app, $_SESSION['username']);

                if(!isset($user_details[0])) // valid user returns associative array, so this ensures that the array is in the correct format
                {
                    $_SESSION['User'] = new UserModel($user_details['UserID'], $user_details["Username"], $user_details["UserFirstName"],
                                                        $user_details["UserLastName"],  $user_details["UserTypeID"]);

                    logAttemptToDatabase($app, $user_id_result, $user_authenticated_result);
                    $response->getBody()->write(json_encode("Login attempt successful"));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
                }

                else
                {
                    logAttemptToDatabase($app, $user_id_result, $user_authenticated_result);
                    $response->getBody()->write(json_encode($user_details[0]));
                    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                }
                break;

            case false:
                $user_authenticated_result=0;
                $response->getBody()->write(json_encode("Incorrect password"));
                logAttemptToDatabase($app, $user_id_result, $user_authenticated_result);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
                break;
        }
    }
})->setName('api_login');

function getUserID($app, $username)
{
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $userid = $model->getUserID($username);

    return $userid;
}

function getUserPassword($app, $userid, $username)
{
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $password_result = $model->getUserPassword($userid, $username);

    return $password_result;
}

function getUserDetails($app, $username)
{
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $user = $model->getUserDetails($username);

    return $user;
}

function logAttemptToDatabase($app, $userid, $login_result)
{
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $model->storeLoginAttempt($userid, $login_result);
}
