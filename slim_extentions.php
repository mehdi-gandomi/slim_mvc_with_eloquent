<?php
// DIC configuration

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
    
//confgiure monolog logger
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};


