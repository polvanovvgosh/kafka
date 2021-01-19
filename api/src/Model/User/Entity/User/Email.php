<?php

declare(strict_types=1);


namespace Api\Model\User\Entity\User;


use Webmozart\Assert\Assert;

class Email
{
    private string $email;

    public function __construct(string $email)
    {
        Assert::notEmpty($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Incorrect email.');
        }

        $this->email = mb_strtolower($email);
    }

    /**
     * @return false|string|string[]
     */
    public function getEmail()
    {
        return $this->email;
    }

}