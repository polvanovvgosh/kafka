<?php

declare(strict_types=1);


namespace Api\Model\User\UseCase\SignUp\Confirm;


use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public string $email;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public string $token;
}