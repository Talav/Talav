<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Model;

trait AggregateTrait
{
    private iterable $events = [];

    public function popEvents(): iterable
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    protected function record(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }
}
