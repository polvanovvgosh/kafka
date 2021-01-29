<?php

declare(strict_types=1);


namespace Api\Infrastructure\Model\User\Service;


use Api\Model\User\Service\PasswordHasher;

class BCryptPasswordHasher implements PasswordHasher
{

    /**
     * @var int
     */
    private int $cost;

    public function __construct(int $cost = 12)
    {
        $this->cost = $cost;
    }

    /**
     * @param string $password
     *
     * @return string
     */
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $this->cost]);
        if ($hash === false) {
            throw new \RuntimeException('Unable tu generate hash.');
        }

        return $hash;
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}