<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Repository;

use Doctrine\ORM\EntityRepository as BaseEntityRepository;

class ResourceRepository extends BaseEntityRepository implements RepositoryInterface
{
    use RepositoryPaginatorTrait;
}
