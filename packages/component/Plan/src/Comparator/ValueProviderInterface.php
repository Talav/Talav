<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Comparator;

use Doctrine\Common\Collections\Collection;

interface ValueProviderInterface
{
    public function getCurrentValuesList(): Collection;
}
