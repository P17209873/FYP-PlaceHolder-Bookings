<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/createnewevent', function (Request $request, Response $response) use ($app) {

    session_start();

    $tainted_values = $request->getParsedBody();
    $cleaned_values = [];

    $validator = $app->getContainer()->get('validator');

    foreach ($tainted_values as $value) {
        array_push($cleaned_values, $validator->sanitiseString($value));
    }

    if(!isset($_SESSION['EventTypes'])){
        return $response->withHeader('Location', '/')->withStatus(302);
    }

    $event_validation_result = $validator->validateEventDetails($cleaned_values, $_SESSION['EventTypes']);

    if($event_validation_result == true) {
        $event_creation_result = createEventInDatabase($app, $cleaned_values);


        if($event_creation_result == true){
            $response->getBody()->write(json_encode(true));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }

        else {
            $response->getBody()->write(json_encode("Database connection error."));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    else {
        $response->getBody()->write(json_encode("Invalid input."));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }



})->setName('createanevent');

function createEventInDatabase($app, $event_details) {

    $valid = false;
    $verification = false;

    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $model = $app->getContainer()->get('eventModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $event_type_id = $model->getEventTypeId($event_details[2]);
    $event_details[5] = $_SESSION['User']->getUserID();

    if($event_type_id !== 'Unfortunately there was a query error. Please try again later.'){
        $valid = true;
    }

    if($valid){
        $event_details[2] = $event_type_id;

        $event_details[3] = date("Y:m:d H:i:s", strtotime($event_details[3]));
        $event_details[4] = date("Y:m:d H:i:s", strtotime($event_details[4]));

        $verification = $model->createEvent($event_details);
    }


    return $verification;

}
