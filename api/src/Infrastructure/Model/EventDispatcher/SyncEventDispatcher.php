<?php

declare(strict_types=1);


namespace Api\Infrastructure\Model\EventDispatcher;


use Api\Model\EventDispatcher;
use Psr\Container\ContainerInterface;

class SyncEventDispatcher implements EventDispatcher
{

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;
    private array $listeners;

    /**
     * SyncEventDispatcher constructor.
     *
     * @param ContainerInterface $container
     * @param array              $listeners
     */
    public function __construct(ContainerInterface $container, array $listeners)
    {
        $this->container = $container;
        $this->listeners = $listeners;
    }

    public function dispatch(...$events): void
    {
        foreach ($events as $event) {
            $this->dispatchEvent($event);
        }
    }

    private function dispatchEvent($event)
    {
        $eventName = \get_class($event);
        if (array_key_exists($eventName, $this->listeners)) {
            foreach ($this->listeners[$eventName] as $listenerClass) {
                $listener = $this->resolveListener($listenerClass);
                $listener($event);
            }
        }
    }

    private function resolveListener($listenerClass): callable
    {
        return $this->container->get($listenerClass);
    }
}