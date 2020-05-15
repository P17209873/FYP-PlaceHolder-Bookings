<?php

/**
 * Creates the Slim application object, adds necessary dependencies
 * and starts the application.
 */

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;
use Middlewares\TrailingSlash;

require '../vendor/autoload.php';

$settings = require 'app/settings.php';

// Create PHP-DI Container
$container = new Container();
AppFactory::setContainer($container);

$container->set('settings', $settings);

// Require dependencies (Slim Twig View, among other objects)
require 'app/dependencies.php';

// Create app
$app = AppFactory::create();

// Add Twig View Middleware
$app->add(TwigMiddleware::createFromContainer($app));

// Add "Trailing Slash" middlware, preventing Slim from treating /user and /user/ as different URIs
$app->add(new TrailingSlash(false));

// Require routes
require 'app/routes.php';

// Add error middleware
$errorMiddleware = $app->addErrorMiddleware(true,true,true);

// Run application
$app->run();
