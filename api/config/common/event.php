<?php

declare(strict_types=1);

use Api\Infrastructure\Model\EventDispatcher\SyncEventDispatcher;
use Api\Model\EventDispatcher;
use Psr\Container\ContainerInterface;
use Api\Infrastructure\Model\EventDispatcher\Listener;

return [
    EventDispatcher::class => function (ContainerInterface $container) {
        return new SyncEventDispatcher(
            $container,
            [
                Api\Model\User\Entity\User\Event\UserCreated::class => [
                    Listener\User\CreatedListener::class,
                ]
            ]);
    },

    Listener\User\CreatedListener::class => function (ContainerInterface $container) {
      return new Listener\User\CreatedListener(
          $container->get(Swift_Mailer::class),
          $container->get('config')['mailer']['from']
      );
    },
];
