<?php

/**
 * HOME ROUTES
 */

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * The route for the default website URL - if the user is logged in, the route redirects to the logged in homepage,
 * else shows the generic homepage
 */
$app->get('/', function (Request $request, Response $response) use ($app){

    session_start();
    // If user is logged in, redirect to logged in homepage
    if(isset($_SESSION['User'])){
        return $response->withHeader('Location', '/home')->withStatus(302);
    }

    else {

        $html_output = $this->get('view')->render($response, 'homepage.html.twig', [
            'css_path' => CSS_PATH,
            'js_path' => JS_PATH,
            'app_name' => APP_NAME,
            'loggedin' => false,
            'page_title' => 'Home',
            'home_path' => '/',
            'about_path' => '/about',
            'help_path' => '/help',
            'login_path' => '/login',
            'register_path' => '/register'
        ]);

        return $html_output;
    }


})->setName('home');

$app->get('/home', function (Request $request, Response $response) use ($app){
    session_start();

    if(isset($_SESSION['User'])) {
        $user = $_SESSION['User'];

        $recent_events = getPublicEvents($app);


        $html_output = $this->get('view')->render($response, 'user_homepage.html.twig', [
            'css_path' => CSS_PATH,
            'js_path' => JS_PATH,
            'app_name' => APP_NAME,
            'loggedin' => true,
            'page_title' => 'Home',
            'home_path' => '/',
            'view_events_path' => '/events/view',
            'create_event_path' => '/events/create',
            'account_path' => '/user/account',
            'logout_path' => '/api/logout',
            'username' => $user->getUsername(),
            'full_name' => $user->getFullName(),
            'first_name' => $user->getFirstName(),
            'recent_events' => $recent_events

        ]);

        return $html_output;

    }

    //Redirect user to application homepage if not logged in
    else {
        return $response->withHeader('Location', '/')->withStatus(302);
    }
})->setName('userhome');

function getPublicEvents($app)
{
    $settings = $app->getContainer()->get('settings');
    $settings = $settings['settings'];

    $event_model = $app->getContainer()->get('eventModel');

    $event_model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $event_model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $event_model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $data = $event_model->getEventsHomepagePreview();
    return $data;


}


