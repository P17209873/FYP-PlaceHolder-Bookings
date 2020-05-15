<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/updatepassword', function (Request $request, Response $response) use ($app) {
    /*
     * PROCESS:
     * Step 1: Pull hashed user password from database
     * Step 1.5: Sanitise and validate input
     * Step 2: Use BcryptWrapper authenticatePassword to ensure that hashed password matches posted value
     * Step 3: Check that newPass and newPassRepeat are identical
     * Step 4: Created hashed password using BcryptWrapper
     * Step 5: Update database values
     */

    session_start();

    $validator = $app->getContainer()->get('validator');
    $bcrypt = $app->getContainer()->get('bcryptWrapper');

    $session_valid = isset($_SESSION['User']);
    $request_body = $request->getParsedBody();

    for($i = 0; $i > count($request_body); $i++ ){
        $request_body[$i] = $validator->sanitiseString($request_body[$i]);
    }

    $hashed_password = "";
    $valid_new_passwords = ($request_body[1] === $request_body[2]);

    if($session_valid)
    {
        // makes use of helper function from api/login.php
        $hashed_password = getUserPassword($app, $_SESSION['User']->getUserID(), $_SESSION['User']->getUsername());
    }

    if($bcrypt->authenticatePassword($request_body[0], $hashed_password) && $valid_new_passwords)
    {
        $new_hashed_password = $bcrypt->createHashedPassword($request_body[1]);

        $settings = $app->getContainer()->get('settings');
        $settings = $settings['settings'];

        $_SESSION['User']->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
        $_SESSION['User']->setDatabaseConnectionSettings($settings['pdo_settings']);
        $_SESSION['User']->setSqlQueries($app->getContainer()->get('sqlQueries'));

        $_SESSION['User']->updatePassword($_SESSION['User']->getUserID(), $new_hashed_password);
        $_SESSION['User']->clearDatabaseSettings();

        $response->getBody()->write(json_encode(true));
        return $response;

    }

    $response->getBody()->write(json_encode(false));
    return $response;

});

