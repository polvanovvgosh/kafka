<?php

declare(strict_types=1);

namespace Api\Model\User\Entity\User;

class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    private UserId $id;
    private \DateTimeImmutable $createdAt;
    private Email $email;
    private string $passwordHash;
    private ConfirmToken $confirmToken;
    private string $status;

    /**
     * User constructor.
     *
     * @param UserId $id
     * @param \DateTimeImmutable $createdAt
     * @param Email $email
     * @param string $hash
     * @param ConfirmToken $confirmToken
     */
    public function __construct(
        UserId $id,
        \DateTimeImmutable $createdAt,
        Email $email,
        string $hash,
        ConfirmToken $confirmToken
    )
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmToken = $confirmToken;
        $this->status = self::STATUS_WAIT;
    }

    /**
     * @return UserId
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return ConfirmToken
     */
    public function getConfirmToken(): ConfirmToken
    {
        return $this->confirmToken;
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}