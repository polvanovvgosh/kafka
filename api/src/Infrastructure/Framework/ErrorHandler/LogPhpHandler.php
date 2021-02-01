<?php

declare(strict_types=1);


namespace Api\Infrastructure\Framework\ErrorHandler;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Handlers\PhpError;

class LogPhpHandler extends PhpError
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * LogPhpHandler constructor.
     *
     * @param LoggerInterface $logger
     * @param false           $displayErrorDetails
     */
    public function __construct(LoggerInterface $logger, $displayErrorDetails = false)
    {
        parent::__construct($displayErrorDetails);
        $this->logger = $logger;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param \Throwable             $error
     *
     * @return ResponseInterface|void
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, \Throwable $error)
    {
        $this->logger->error($error->getMessage(), ['exception' => $error]);

        parent::__invoke($request, $response, $error);
    }
}