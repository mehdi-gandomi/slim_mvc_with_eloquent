<?php
 
namespace Core;
 
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
 
final class ErrorLogger
{

 
    public function __invoke(Request $request, Response $response, \Exception $exception)
    {
        // Log the message
        var_dump($exception);
    }
}