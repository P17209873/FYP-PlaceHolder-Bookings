<?php

/**
 * A file that is used to separate the public facing and private file structure, severely reducing the likelihood of
 * any exposure of the application source files to a third party. The file also 'builds' the application, requiring the
 * autoload.php file from Composer's vendor folder, the settings.php file, dependencies.php file, and routes.php file.
 * These files are then used to initiate the Slim app object.
 */

ini_set('display_errors', 'On');
ini_set('html_errors', 'On');

define('DIRSEP', DIRECTORY_SEPARATOR);

$url_root = $_SERVER['SCRIPT_NAME'];
$url_root = implode('/', explode('/', $url_root, -1));
$css_path = $url_root . '/css/bootstrap.css';
$js_path = $url_root . '/js/';

define('BCRYPT_ALGO', PASSWORD_DEFAULT);
define('BCRYPT_COST', 12);

define('JS_PATH', $js_path);
define('CSS_PATH', $css_path);
define('APP_NAME', 'PlaceHolder Bookings');
define('LANDING_PAGE', $_SERVER['SCRIPT_NAME']);

define('LOG_FILE_LOCATION', '../logs/');
define('LOG_FILE_NAME', 'PlaceHolder.log');

$settings = array (
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'mode' => 'development',
        'debug' => true,
        'class_path' => __DIR__ . '/src/',
        'view' => [
            'template_path' => __DIR__ . '/templates/',
            'twig' => [
                'cache' => false,
                'auto_reload' => true,
            ]
        ],

    'pdo_settings' => [
        'rdbms' => 'mysql',
        'host' => 'localhost',
        'db_name' => 'PlaceHolder',
        'port' => '3306',
        'user_name' => 'PlaceHolderUser',
        'user_password' => 'ubG4hofF',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'options' => [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => true,
        ],
    ]
]);

return $settings;
