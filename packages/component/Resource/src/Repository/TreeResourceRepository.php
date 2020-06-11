<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class TreeResourceRepository extends NestedTreeRepository implements RepositoryInterface
{
    use RepositoryPaginatorTrait;
}
