<?php
use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers;
session_start();
// Routes

// $app->get('/[{name}]', function (Request $request, Response $response, array $args) {
//     // Sample log message
//     $this->logger->info("Slim-Skeleton '/' route");

//     // Render index view
//     return $this->renderer->render($response, 'index.phtml', $args);
// });

// GET request on homepage, simply show the view template index.twig
// $app->get('/','\App\controllers\Posts:index');
$container = $app->getContainer();
$app->get('/',function (Request $request, Response $response, array $args){
    $this->view->render($response,"home.twig",["page_title"=>"صفحه اصلی"]);
});
$app->get('/exception',function (Request $request, Response $response, array $args){
    throw new Exception("fuck");
});
$app->get('/post/{id}',"PostController:one_post");

