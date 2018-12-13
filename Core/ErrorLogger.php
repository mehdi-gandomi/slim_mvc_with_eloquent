<?php

namespace Core;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Monolog\Logger;

final class ErrorLogger extends \Slim\Handlers\Error
{
    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    public function __invoke(Request $request, Response $response, \Throwable $exception)
    {
        // Log the message
        $this->logger->critical($exception->getMessage());

        return parent::__invoke($request, $response, $exception);
    }
}