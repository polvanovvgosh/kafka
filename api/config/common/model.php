<?php

declare(strict_types=1);

use Api\Infrastructure\Model\Service\DoctrineFlusher;
use Api\Infrastructure\Model\User as UserInfrastructure;
use Api\Model\User as UserModel;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return [
     UserModel\Service\PasswordHasher::class => function () {
        return new UserInfrastructure\Service\BCryptPasswordHasher();
    },

    UserModel\Service\ConfirmTokenizer::class => function (ContainerInterface $container) {
        $interval = $container->get('config')['auth']['signup_confirm_interval'];
        return new UserInfrastructure\Service\RandomConfirmTokenizer(new \DateInterval($interval));
    },

    Api\Model\Flusher::class => function (ContainerInterface $container) {
        $em = $container->get(EntityManagerInterface::class);

        return new DoctrineFlusher($em);
    },

     UserModel\Entity\User\UserRepository::class => function (ContainerInterface $container) {
         return new UserInfrastructure\Entity\DoctrineUserRepository($container->get(EntityManagerInterface::class));
     },

     UserModel\UseCase\SignUp\Request\Handler::class => function (ContainerInterface $container) {
         return new UserModel\UseCase\SignUp\Request\Handler(
             $container->get(UserModel\Entity\User\UserRepository::class),
             $container->get(UserModel\Service\PasswordHasher::class),
             $container->get(UserModel\Service\ConfirmTokenizer::class),
             $container->get(Api\Model\Flusher::class)
         );
     },
     UserModel\UseCase\SignUp\Confirm\Handler::class => function (ContainerInterface $container) {
        return new UserModel\UseCase\SignUp\Confirm\Handler(
            $container->get(UserModel\Entity\User\UserRepository::class),
            $container->get(Api\Model\Flusher::class)
        );
     },

    'config' => [
        'auth' => [
            'signup_confirm_interval' => 'PT5M',
        ]
    ]
];