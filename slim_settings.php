<?php
use App\Config;
return array(
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'db' => [
            'driver' => Config::DB_DRIVER,
            'host' => Config::DB_HOST,
            'port'=>Config::DB_PORT,
            'database' => Config::DB_NAME,
            'username' => Config::DB_USER,
            'password' => Config::DB_PASSWORD,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => Config::TABLE_PREFIX
           
        ]
    ],
);

// Register provider
// $c['flash'] = function () {
//     return new \Slim\Flash\Messages();
// };


