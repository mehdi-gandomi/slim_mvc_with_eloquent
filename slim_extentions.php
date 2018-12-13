<?php
// DIC configuration
use App\Config;
$container = $app->getContainer();

// Config App Container

$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('App/views');
    
    // Instantiate and add Slim specific extension
    $router = $c->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));
    // $view->addExtension(new Knlv\Slim\Views\TwigMessages(
    //     new Slim\Flash\Messages()
    // ));
    // $view->getEnvironment()->addGlobal("user", array(
    //     'is_logged_in'=>isset($_SESSION['LogedIn']) || isset($_SESSION['userLogedIn']),
    //     'fullname'=>isset($_SESSION['name']) ? $_SESSION['name']:false
    // ));
    return $view;
};
$container['notFoundHandler'] = function ($c) {
    return function (\Slim\Http\Request $request, \Slim\Http\Response $response) use ($c) {
        $view = $c->get('view');
        $view->render($response, '404.twig');
        return $response;
    };
};


$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
//confgiure monolog logger
if(Config::LOG_ERRORS){
    
    $container['Logger'] = function($c) {
        $logger = new Monolog\Logger('logger');
        $filename =__DIR__. '/logs/error.log';
        $stream = new Monolog\Handler\StreamHandler($filename, Monolog\Logger::DEBUG);
        $fingersCrossed = new Monolog\Handler\FingersCrossedHandler(
            $stream, Monolog\Logger::ERROR);
        $logger->pushHandler($fingersCrossed);
        return $logger;
    };
    $container['errorHandler'] = function ($c) {
        return new Core\ErrorLogger($c['Logger']);
    };
    $container['phpErrorHandler'] = function ($c) {
        return $c['errorHandler'];
    };
}



