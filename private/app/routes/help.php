<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/help', function (Request $request, Response $response) use ($app){

    $html_output = $this->get('view')->render($response, 'help.html.twig', [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'app_name' => APP_NAME,
        'page_title' => 'Help',
        'home_path' => '/',
        'about_path' => '/about',
        'help_path' => '/help',
        'login_path' => '/login',
        'register_path' => '/register'
    ]);

    return $html_output;

})->setName('help');