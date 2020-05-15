<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/register', function (Request $request, Response $response) use ($app){

    $html_output = $this->get('view')->render($response, 'registration.html.twig', [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH . 'register.js',
        'app_name' => APP_NAME,
        'page_title' => 'Register',
        'home_path' => '/',
        'about_path' => '/about',
        'help_path' => '/help',
        'login_path' => '/login',
        'register_path' => '/register'
    ]);

    return $html_output;

})->setName('register');
