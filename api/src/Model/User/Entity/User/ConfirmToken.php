<?php

declare(strict_types=1);

namespace Api\Model\User\Entity\User;

use Webmozart\Assert\Assert;

class ConfirmToken
{
    /**
     * @var string
     */
    private string $token;
    /**
     * @var \DateTimeImmutable
     */
    private \DateTimeImmutable $expires;

    /**
     * ConfirmToken constructor.
     *
     * @param string $token
     * @param \DateTimeImmutable $expires
     */
    public function __construct(string $token, \DateTimeImmutable $expires)
    {
        Assert::notEmpty($token);
        $this->token = $token;
        $this->expires = $expires;
    }

    /**
     * @param \DateTimeImmutable $date
     *
     * @return bool
     */
    public function isExpiredTo(\DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isEqualTo(string $token): bool
    {
        return $this->token === $token;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }


}