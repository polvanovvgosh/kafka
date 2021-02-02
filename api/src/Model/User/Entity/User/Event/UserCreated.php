<?php

declare(strict_types=1);


namespace Api\Model\User\Entity\User\Event;


use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\UserId;

class UserCreated
{
    /**
     * @var UserId
     */
    public UserId $id;
    /**
     * @var Email
     */
    public Email $email;
    /**
     * @var ConfirmToken
     */
    public ConfirmToken $confirmToken;

    /**
     * UserCreated constructor.
     *
     * @param UserId       $id
     * @param Email        $email
     * @param ConfirmToken $confirmToken
     */
    public function __construct(UserId $id, Email $email, ConfirmToken $confirmToken)
    {
        $this->id = $id;
        $this->email = $email;
        $this->confirmToken = $confirmToken;
    }
}