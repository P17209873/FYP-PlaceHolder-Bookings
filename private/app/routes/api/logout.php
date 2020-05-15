<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/logout', function (Request $request, Response $response) use ($app) {

    session_start();

    if(isset($_SESSION['User'])) {
        unset($_SESSION['User']);
        return $response->withHeader('Location', '/')->withStatus(302);
    }

    else {
        return $response->getBody()->write("Oops!");
    }
});
