<?php

declare(strict_types=1);


namespace Api\Model\User\UseCase\SignUp\Request;


use Api\Model\Flusher;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\User;
use Api\Model\User\Entity\User\UserId;
use Api\Model\User\Entity\User\UserRepository;
use Api\Model\User\Service\ConfirmTokenizer;
use Api\Model\User\Service\PasswordHasher;

class Handler
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var PasswordHasher
     */
    private PasswordHasher $hasher;
    /**
     * @var ConfirmTokenizer
     */
    private ConfirmTokenizer $tokenizer;
    /**
     * @var Flusher
     */
    private Flusher $flusher;

    public function __construct(
        UserRepository $userRepository,
        PasswordHasher $hasher,
        ConfirmTokenizer $tokenizer,
        Flusher $flusher
    )
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->userRepository->hasByEmail($email)) {
            throw new \DomainException('User with this email already exists.');
        }

        $user = new User(
            UserId::next(),
            new \DateTimeImmutable(),
            $email,
            $this->hasher->hash($command->password),
            $token = $this->tokenizer->generate()
        );

        $this->userRepository->add($user);

        $this->flusher->flush($user);
    }
}