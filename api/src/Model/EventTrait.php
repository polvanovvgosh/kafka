<?php

declare(strict_types=1);


namespace Api\Model;


trait EventTrait
{
    /**
     * @var array
     */
    private array $recordedEvents = [];

    /**
     * @param $event
     */
    public function recordEvent($event): void
    {
        $this->recordedEvents[] = $event;
    }

    /**
     * @return array
     */
    public function releaseEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }
}