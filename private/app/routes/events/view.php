<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/events/view', function (Request $request, Response $response) use ($app) {

    session_start();

    if(isset($_SESSION['User'])){

        $event_model = $app->getContainer()->get('eventModel');
        $events = getEvents($app, $event_model);

        $html_output = $this->get('view')->render($response, 'view_events.html.twig', [
            'css_path' => CSS_PATH,
            'js_path' => JS_PATH,
            'app_name' => APP_NAME,
            'page_title' => 'View Events',
            'home_path' => '/',
            'loggedin' => true,
            'events' => $events,
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

})->setName('eventsview');

$app->get('/events/view/{id}', function (Request $request, Response $response, $args) use ($app){
    session_start();

    if(isset($_SESSION['User'])){

        $event_model = $app->getContainer()->get('eventModel');
        $event_data = getEvent($app, $event_model, $args['id']);

        if(!isset($_SESSION['EventTypes'])){
            $_SESSION['EventTypes'] = getEventTypes($app, $event_model); //calls helper function from create.php
        }

        $types = $_SESSION['EventTypes'];

        if(!isset($event_data[0])){
            $user_result = ($event_data['Username'] == $_SESSION['User']->getUserName());
            $register_result = $event_model->getEventRegistrationResult($args['id'], $_SESSION['User']->getUserID());

            $html_output = $this->get('view')->render($response, 'event_details.html.twig', [
                'css_path' => CSS_PATH,
                'js_path' => JS_PATH . 'event.js',
                'app_name' => APP_NAME,
                'page_title' => 'Viewing: '. $event_data['EventName'],
                'home_path' => '/',
                'loggedin' => true,
                'event' => $event_data,
                'view_events_path' => '/events/view',
                'create_event_path' => '/events/create',
                'account_path' => '/user/account',
                'logout_path' => '/api/logout',
                'user_result' => $user_result,
                'types' => $types,
                'register_result' => $register_result
            ]);
        }

        else {
            $html_output = $this->get('view')->render($response, 'event404.html.twig', [
                'css_path' => CSS_PATH,
                'js_path' => JS_PATH,
                'app_name' => APP_NAME,
                'page_title' => 'Whoops!',
                'home_path' => '/',
                'loggedin' => true,
                'view_events_path' => '/events/view',
                'create_event_path' => '/events/create',
                'account_path' => '/user/account',
                'logout_path' => '/api/logout'
            ]);
        }




        return $html_output;

    }

    else {
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
})->setName('eventview');

function getEvents($app, $event) {
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];
    $event->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $event->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $event->setDatabaseConnectionSettings($settings['pdo_settings']);
    $data = $event->getAllEvents();

    return $data;
}

function getEvent($app, $event_model, $event_id) {
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];
    $event_model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $event_model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $event_model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $data = $event_model->getEvent($event_id);

    return $data;
}
