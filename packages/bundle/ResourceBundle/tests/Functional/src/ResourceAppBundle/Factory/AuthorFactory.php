<?php

declare(strict_types=1);

namespace ResourceAppBundle\Factory;

use ResourceAppBundle\Entity\Author;
use Talav\Component\Resource\Model\ResourceInterface;

final class AuthorFactory implements AuthorFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(): ResourceInterface
    {
        return new Author();
    }
}
