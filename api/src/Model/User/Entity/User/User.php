<?php

declare(strict_types=1);

namespace Api\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @UniqueConstraint(columns={"name", "email"})
 *     })
 */
class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    /**
     * @var UserId
     * @ORM\Column(type="user_user_id")
     */
    private UserId $id;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="date_immutable")
     */
    private \DateTimeImmutable $createdAt;
    /**
     * @var Email
     * @ORM\Column(type="user_user_email")
     */
    private Email $email;
    /**
     * @var string
     * @ORM\Column(type="string", name="password_hash")
     */
    private string $passwordHash;
    /**
     * @var ConfirmToken
     * @ORM\Embedded(class="ConfirmToken", columnPrefix="confirm_token")
     */
    private $confirmToken;
    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
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
     * @return ConfirmToken|null
     */
    public function getConfirmToken(): ?ConfirmToken
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

    /**
     * @param string $token
     * @param \DateTimeImmutable $date
     */
    public function confirmSignUp(string $token, \DateTimeImmutable $date)
    {
        if ($this->isActive()) {
            throw new \DomainException('User is already active.');
        }
        if (!$this->confirmToken->isEqualTo($token)) {
            throw new \DomainException('Confirm token is invalid.');
        }
        if ($this->confirmToken->isExpiredTo($date)) {
            throw new \DomainException('Confirm token is expired.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;

    }
}