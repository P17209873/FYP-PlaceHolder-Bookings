<?php

use PlaceHolder\EventModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/events/create', function (Request $request, Response $response) use ($app) {

    session_start();

    if(isset($_SESSION['User'])){

        $event = $app->getContainer()->get('eventModel');
        $types = getEventTypes($app, $event);
        $_SESSION['EventTypes'] = $types;

        $html_output = $this->get('view')->render($response, 'create_event.html.twig', [
            'css_path' => CSS_PATH,
            'js_path' => JS_PATH . 'eventcreate.js',
            'app_name' => APP_NAME,
            'page_title' => 'Create an Event',
            'home_path' => '/',
            'loggedin'=>true,
            'types' => $types,
            'view_events_path' => '/events/view',
            'create_event_path' => '/events/create',
            'account_path' => '/user/account',
            'logout_path' => '/api/logout'
        ]);

        return $html_output;

    }

    else {
        return $response->withHeader('Location', '/login')->withStatus(302);
    }

})->setName('eventcreation');

function getEventTypes($app, $event) {
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];
    $event->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $event->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $event->setDatabaseConnectionSettings($settings['pdo_settings']);
    $types = $event->getEventTypes();

    return $types;
}