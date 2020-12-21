<?php

declare(strict_types=1);


namespace Api\Test\Unit\Model\User\Entity\User;


use Api\Model\User\Entity\User\ConfirmToken;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\User;
use Api\Model\User\Entity\User\UserId;
use PHPUnit\Framework\TestCase;

class SingUpTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new User(
            $id = UserId::next(),
            $createdAt = new \DateTimeImmutable(),
            $email = new Email('example@mail.com'),
            $hash = 'hash',
            $token = new ConfirmToken('token', new \DateTimeImmutable('+1 day'))
        );

        $this->assertTrue($user->isWait());
        $this->assertFalse($user->isActive());

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($createdAt, $user->getCreatedAt());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($hash, $user->getPasswordHash());
        $this->assertEquals($token, $user->getConfirmToken());
    }

}