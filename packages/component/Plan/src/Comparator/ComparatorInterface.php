<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Comparator;

interface ComparatorInterface
{
    public function isAllowed($key): bool;
}
