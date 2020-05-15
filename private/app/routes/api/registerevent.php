<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/registerevent', function (Request $request, Response $response) use ($app) {

    session_start();

    $session_valid = isset($_SESSION['User']);
    $booking_valid = false;
    $booking_exists = false;

    $request_body = $request->getParsedBody();
    $event_model = $app->getContainer()->get('eventModel');

    if($session_valid) {
        $user_id = $_SESSION['User']->getUserID();
        $event_id = getEventId($app, $event_model, $request_body);
        $booking_exists = $event_model->getEventRegistrationResult($event_id, $user_id);
        if(!$booking_exists) {
            $booking_valid = createBooking($app, $event_model, $event_id, $user_id);
        }
    }

    if($booking_valid !== false) {
        $response->getBody()->write(json_encode(true));
        return $response;
    }

    else {
        $response->getBody()->write(json_encode(false));
        return $response;
    }

})->setName('registerevent');

function createBooking($app, $event_model, $event_id, $user_id) {

    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $event_model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $event_model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $event_model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $event_model->createBooking($event_id, $user_id);

}