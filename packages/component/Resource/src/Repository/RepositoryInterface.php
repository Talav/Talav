<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Pagerfanta\Pagerfanta;

interface RepositoryInterface extends ObjectRepository
{
    public const ORDER_ASCENDING = 'ASC';
    public const ORDER_DESCENDING = 'DESC';

    /**
     * @param array $criteria
     * @param array $sorting
     *
     * @return Pagerfanta
     */
    public function createPaginator(array $criteria = [], array $sorting = []): Pagerfanta;
}