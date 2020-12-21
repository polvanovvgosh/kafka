<?php

declare(strict_types=1);

namespace Api\Model\User\UseCase\SingUp\Request;


class Command
{
    public string $email;
    public string $password;
}