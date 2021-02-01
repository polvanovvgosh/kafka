<?php

declare(strict_types=1);

namespace Api\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\This;
use Webmozart\Assert\Assert;

/**
 * Class ConfirmToken
 * @ORM\Embeddable()
 *
 */
class ConfirmToken
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private string $token;
    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="date_immutable", nullable=true )
     */
    private \DateTimeImmutable $expires;

    /**
     * ConfirmToken constructor.
     *
     * @param string             $token
     * @param \DateTimeImmutable $expires
     *
     */
    public function __construct(string $token, \DateTimeImmutable $expires)
    {
        Assert::notEmpty($token);
        $this->token   = $token;
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

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->token);
    }

    public function validate(string $token, \DateTimeImmutable $date): void
    {
        if (!$this->isEqualTo($token)) {
            throw new \DomainException('Confirm token is invalid.');
        }
        if ($this->isExpiredTo($date)) {
            throw new \DomainException('Confirm token is expired.');
        }

    }


}