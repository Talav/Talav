<?php

declare(strict_types=1);

namespace Talav\Component\Plan\Tests\Helper;

use Doctrine\Common\Collections\Collection;
use Talav\Component\Plan\Comparator\ValueProviderInterface;

class ValueProvider implements ValueProviderInterface
{
    private $values;

    public function __construct(Collection $values)
    {
        $this->values = $values;
    }

    public function getCurrentValuesList(): Collection
    {
        return $this->values;
    }
}
