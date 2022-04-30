<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Model;

interface AggregateInterface
{
    public function popEvents(): iterable;
}
