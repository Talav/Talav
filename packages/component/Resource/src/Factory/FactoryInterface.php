<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Factory;

use Talav\Component\Resource\Model\ResourceInterface;

interface FactoryInterface
{
    /**
     * @return ResourceInterface
     */
    public function create(): ResourceInterface;
}
