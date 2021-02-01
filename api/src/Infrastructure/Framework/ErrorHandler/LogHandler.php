<?php

declare(strict_types=1);


namespace Api\Infrastructure\Framework\ErrorHandler;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Handlers\Error;

class LogHandler extends Error
{

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * LogHandler constructor.
     *
     * @param LoggerInterface $logger
     * @param false           $displayErrorDetails
     */
    public function __construct(LoggerInterface $logger, $displayErrorDetails = false)
    {
        parent::__construct($displayErrorDetails);
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, \Exception $exception)
    {
      $this->logger->error($exception->getMessage(), ['exception' => $exception]);

      return parent::__invoke($request, $response, $exception);
    }
}