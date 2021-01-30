<?php

declare(strict_types=1);


namespace Api\Http\Action\Auth\SignUp;


use Api\Model\User\UseCase\SignUp\Confirm\Command;
use Api\Model\User\UseCase\SignUp\Confirm\Handler;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConfirmAction implements RequestHandlerInterface
{

    /**
     * @var Handler
     */
    private Handler $handler;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    public function __construct(Handler $handler, ValidatorInterface $validator)
    {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(),true);

        $command = new Command();
        $command->email = $body['email'] ?? '';
        $command->token = $body['token'] ?? '';

        $violations = $this->validator->validate($command);
        if ($violations->count() > 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new JsonResponse(['errors' => $errors], 400);
        }

        $this->handler->handle($command);

        return new JsonResponse([]);
    }
}