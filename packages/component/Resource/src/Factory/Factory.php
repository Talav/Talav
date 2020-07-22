<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Factory;

use Talav\Component\Resource\Model\ResourceInterface;

/**
 * Creates resources based on theirs FQCN.
 */
final class Factory implements FactoryInterface
{
    private string $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * {@inheritdoc}
     */
    public function create(): ResourceInterface
    {
        return new $this->className();
    }
}
