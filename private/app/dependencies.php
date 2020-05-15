<?php

/**
 * DEPENDENCIES
 */

use Slim\Views\Twig;

 // Adds Slim view to container
$container->set('view', function(){
    return Twig::create(__DIR__ . '/templates/', ['cache' => false]);
});

$container->set('sqlQueries', function (){
    $sql_queries = new \PlaceHolder\SQLQueries();
    return $sql_queries;
});

$container->set('databaseWrapper', function(){
   $database_wrapper = new \PlaceHolder\DatabaseWrapper();
   return $database_wrapper;
});

$container->set('registrationModel', function (){
   $registration_model = new \PlaceHolder\RegistrationModel();
   return $registration_model;
});

$container->set('loginModel', function (){
    $login_model = new \PlaceHolder\LoginModel();
    return $login_model;
});

$container->set('monologWrapper', function (){
    $logger = new \PlaceHolder\MonologWrapper();
    return $logger;
});

$container->set('validator', function(){
   $validator = new \PlaceHolder\Validator();
   return $validator;
});

$container->set('bcryptWrapper', function(){
    $validator = new \PlaceHolder\BcryptWrapper();
    return $validator;
});

$container->set('eventModel', function(){
   $eventModel = new \PlaceHolder\EventModel();
   return $eventModel;
});
