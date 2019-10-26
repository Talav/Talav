<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Metadata;

final class Registry implements RegistryInterface
{
    /**
     * @var array|MetadataInterface[]
     */
    private $metadata = [];

    /**
     * {@inheritdoc}
     */
    public function getAll(): iterable
    {
        return $this->metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $alias): MetadataInterface
    {
        if (!array_key_exists($alias, $this->metadata)) {
            throw new \InvalidArgumentException(sprintf('Resource "%s" does not exist.', $alias));
        }
        return $this->metadata[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function getByClass(string $className): MetadataInterface
    {
        foreach ($this->metadata as $metadata) {
            if ($className === $metadata->getClass('model')) {
                return $metadata;
            }
        }
        throw new \InvalidArgumentException(sprintf('Resource with model class "%s" does not exist.', $className));
    }

    /**
     * {@inheritdoc}
     */
    public function add(MetadataInterface $metadata): void
    {
        $this->metadata[$metadata->getAlias()] = $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function addFromAliasAndConfiguration(string $alias, array $configuration): void
    {
        $this->add(Metadata::fromAliasAndConfiguration($alias, $configuration));
    }
}