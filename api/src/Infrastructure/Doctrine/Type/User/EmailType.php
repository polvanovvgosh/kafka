<?php

declare(strict_types=1);


namespace Api\Infrastructure\Doctrine\Type\User;


use Api\Model\User\Entity\User\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class EmailType extends StringType
{
    public const NAME = 'user_user_email';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Email  ? $value->getEmail() : $value;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return Email|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
       return !empty($value) ? new Email($value) : null;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }
}