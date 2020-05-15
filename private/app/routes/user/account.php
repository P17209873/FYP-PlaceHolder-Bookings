<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/user/account', function (Request $request, Response $response) use ($app){

    session_start();

    if(isset($_SESSION['User'])) {
        $user = $_SESSION['User'];

        $user_is_admin = $user->getIsAdmin();
        $user_created_events = getCreatedEvents($app, $user, $user->getUserID());
        $user_booked_events = getBookedEvents($app, $user, $user->getUserID());
        $all_events = getAllEvents($app);
        $user_count = getUserCount($app, $_SESSION['User']);
        $admin_all_users = null;

        if(isset($user_created_events[0]) && $user_created_events[0] === true)
        {
            $user_created_events = false;
        }

        if(isset($user_booked_events[0]) && $user_booked_events[0] === true)
        {
            $user_booked_events = false;
        }

        if($user_is_admin)
        {
            $admin_all_users = adminGetUserInfo($app, $_SESSION['User']);
        }

        $html_output = $this->get('view')->render($response, 'user_viewaccount.html.twig', [
            'css_path' => CSS_PATH,
            'js_path' => JS_PATH . 'useraccount.js',
            'app_name' => APP_NAME,
            'loggedin' => true,
            'adminuser' => $user_is_admin,
            'page_title' => 'My Account',
            'home_path' => '/',
            'view_events_path' => '/events/view',
            'create_event_path' => '/events/create',
            'account_path' => '/user/account',
            'logout_path' => '/api/logout',
            'username' => $user->getUserName(),
            'user_fullname' => $user->getFullName(),
            'user_firstname' => $user->getFirstName(),
            'user_lastname' => $user->getLastName(),
            'booked_events' => $user_booked_events,
            'created_events' => $user_created_events,
            'database_events' => $all_events,
            'user_count' => $user_count[0],
            'event_count' => count($all_events),
            'database_users' => $admin_all_users
        ]);

        return $html_output;
    }

    else {
        return $response->withHeader('Location', '/login')->withStatus(302);
    }

})->setName('user_account');

function getCreatedEvents($app, $user, $user_id) {
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];
    $user->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $user->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $user->setDatabaseConnectionSettings($settings['pdo_settings']);
    $data = $user->getCreatedEvents($user_id);
    $user->clearDatabaseSettings();

    return $data;
}

function getBookedEvents($app, $user, $user_id) {
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];
    $user->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $user->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $user->setDatabaseConnectionSettings($settings['pdo_settings']);
    $data = $user->getBookedEvents($user_id);
    $user->clearDatabaseSettings();

    return $data;
}

function getAllEvents($app)
{
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $event_model = $app->getContainer()->get('eventModel');

    $event_model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $event_model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $event_model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $data = $event_model->getAllEvents();

    return $data;
}

function getUserCount($app, $user)
{
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $user->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $user->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $user->setDatabaseConnectionSettings($settings['pdo_settings']);
    $data = $user->getUserCount();
    $user->clearDatabaseSettings();

    return $data;
}

function adminGetUserInfo($app, $user)
{
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $user->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $user->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $user->setDatabaseConnectionSettings($settings['pdo_settings']);
    $data = $user->adminGetAllUsers();
    $user->clearDatabaseSettings();

    return $data;
}
