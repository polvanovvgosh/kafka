<?php

declare(strict_types=1);


namespace Api\Http\Action\Auth\SignUp;


use Api\Http\ValidationException;
use Api\Http\Validator\Validator;
use Api\Model\User\UseCase\SignUp\Request\Command;
use Api\Model\User\UseCase\SignUp\Request\Handler;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestAction implements RequestHandlerInterface
{

    /**
     * @var Handler
     */
    private Handler $handler;
    /**
     * @var Validator
     */
    private Validator $validator;

    public function __construct(Handler $handler, Validator $validator)
    {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $command = new Command();

        $command->email = $body['email'] ?? '';
        $command->password = $body['password'] ?? '';

        if ($errors = $this->validator->validate($command)) {
            throw new ValidationException($errors);
        }

        $this->handler->handle($command);

        return new JsonResponse(
            [
                'email' => $command->email,
            ], 201
        );
    }
}