<?php

declare(strict_types=1);


namespace Api\Http\Action\Auth\SignUp;


use Api\Model\User\UseCase\SignUp\Confirm\Command;
use Api\Model\User\UseCase\SignUp\Confirm\Handler;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ConfirmAction implements RequestHandlerInterface
{

    /**
     * @var Handler
     */
    private Handler $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(),true);

        $command = new Command();
        $command->email = $body['email'] ?? '';
        $command->token = $body['token'] ?? '';

        $this->handler->handle($command);

        return new JsonResponse([]);
    }
}