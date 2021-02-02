<?php

declare(strict_types=1);


namespace Api\Model;


interface AggregateRoot
{
    /**
     * @return array
     */
    public function releaseEvents(): array;
}