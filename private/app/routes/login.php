<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/login', function (Request $request, Response $response) use ($app){

    $html_output = $this->get('view')->render($response, 'login.html.twig', [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH . 'login.js',
        'app_name' => APP_NAME,
        'page_title' => 'Log In',
        'home_path' => '/',
        'about_path' => '/about',
        'help_path' => '/help',
        'login_path' => '/login',
        'register_path' => '/register'
    ]);

    return $html_output;

})->setName('login');