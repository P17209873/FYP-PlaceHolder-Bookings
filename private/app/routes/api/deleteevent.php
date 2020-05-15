<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/deleteevent', function (Request $request, Response $response) use ($app) {

    session_start();

    if(!isset($_SESSION['User'])) {
        $response->getBody()->write(json_encode(false));
        return $response;
    }

    $event_model = $app->getContainer()->get('eventModel');
    $request_body = $request->getParsedBody();

    if ($request_body === null) {
        $response->getBody()->write(json_encode("Request body is empty"));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $event_id = getEventId($app, $event_model, $request_body); //uses helper function declared in updateevent.php

    $event_type_id = $event_model->getEventTypeId($request_body[2]); //uses helper function declared in updateevent.php
    $request_body[2] = $event_type_id;

    $user_id = $_SESSION['User']->getUserID();
    $user_created_id = getUserCreatedID($app, $event_model, $event_id);

    if($user_created_id == $user_id && $event_id[0] != false){
        $delete_event_result = deleteEvent($app, $event_model, $event_id);

        if($delete_event_result == null){
            $response->getBody()->write(json_encode(true));
            return $response;
        }
    }

    $response->getBody()->write(json_encode(false));
    return $response;

});

function deleteEvent($app, $event_model, $event_id) {

    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $event_model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $event_model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $event_model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $event_model->deleteEvent($event_id);

}
