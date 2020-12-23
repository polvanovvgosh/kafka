<?php

declare(strict_types=1);


namespace Api\Model\User\UseCase\SingUp\Confirm;


use Api\Model\Flusher;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\UserRepository;

class Handler
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var Flusher
     */
    private Flusher $flusher;

    public function __construct(
       UserRepository $userRepository,
       Flusher $flusher
   )
   {
       $this->userRepository = $userRepository;
       $this->flusher = $flusher;
   }

    public function handler(Command $command)
    {
        $user = $this->userRepository->getUserByEmail(new Email($command->email));

        $user->confirmSignUp($command->token, new \DateTimeImmutable());

        $this->flusher->flush();
    }
}