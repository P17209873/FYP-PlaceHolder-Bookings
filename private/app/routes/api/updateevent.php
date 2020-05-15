<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/updateevent', function (Request $request, Response $response) use ($app) {

    session_start();

    if(!isset($_SESSION['User'])) {
        $response->getBody()->write(json_encode(false));
        return $response;
    }

    $validator = $app->getContainer()->get('validator');
    $event_model = $app->getContainer()->get('eventModel');
    $request_body = $request->getParsedBody();

    if(!isset($_SESSION['EventTypes'])){
        $_SESSION['EventTypes'] = getEventTypes($app, $event_model); //calls helper function from create.php
    }

    $types = $_SESSION['EventTypes'];

    if ($request_body === null) {
        $response->getBody()->write(json_encode("Request body is empty"));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $old_event_details = $request_body[0];
    $new_event_details = $request_body[1];

    $event_id = getEventId($app, $event_model, $old_event_details);

    //remap new event details to ensure that no currently existing db values get overwrote
    for($lcv = 0; $lcv < 5; $lcv += 1) {
        if($old_event_details[$lcv] == $new_event_details[$lcv] || $new_event_details[$lcv] == "") {
            $new_event_details[$lcv] = $old_event_details[$lcv];
        }

        $new_event_details[$lcv] = $validator->sanitiseString($new_event_details[$lcv]);
    }

    $valid = $validator->validateEventDetails($new_event_details, $types);

    $event_type_id = $event_model->getEventTypeId($new_event_details[2]);
    $new_event_details[2] = $event_type_id;

    $user_id = $_SESSION['User']->getUserID();
    $user_created_id = getUserCreatedID($app, $event_model, $event_id);

    if($valid && $user_created_id == $user_id && $event_id[0] != false){
        $update_event_result = updateEvent($app, $event_model, $event_id, $new_event_details);

        if($update_event_result == null){
            $response->getBody()->write(json_encode(true));
            return $response;
        }
    }

    $response->getBody()->write(json_encode(false));
    return $response;

});

function getUserCreatedID($app, $event_model, $event_id) {

    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $event_model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $event_model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $event_model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $event_model->getUserCreatedId($event_id);

}

function getEventId($app, $event_model, $old_event_details) {

    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $event_model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $event_model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $event_model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $event_type_id = $event_model->getEventTypeId($old_event_details[2]);
    $old_event_details[2] = $event_type_id;

    return $event_model->getEventId($old_event_details);

}

function updateEvent($app, $event_model, $event_id, $new_event_details) {

    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $event_model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $event_model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $event_model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $event_model->updateEvent($event_id, $new_event_details);

}
