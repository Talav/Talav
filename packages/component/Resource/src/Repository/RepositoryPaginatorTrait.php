<?php

declare(strict_types=1);

namespace Talav\Component\Resource\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

trait RepositoryPaginatorTrait
{
    /**
     * {@inheritdoc}
     */
    public function createPaginator(array $criteria = [], array $sorting = []): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    protected function getPaginator(QueryBuilder $queryBuilder): Pagerfanta
    {
        // Use output walkers option in DoctrineORMAdapter should be false as it affects performance greatly
        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false, false));
    }

    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        foreach ($criteria as $property => $value) {
            if (!in_array($property, array_merge($this->_class->getAssociationNames(), $this->_class->getFieldNames()), true)) {
                continue;
            }

            $name = $this->getPropertyName($property);

            if (null === $value) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif (is_array($value)) {
                $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
            } elseif ('' !== $value) {
                $parameter = str_replace('.', '_', $property);
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($name, ':' . $parameter))
                    ->setParameter($parameter, $value)
                ;
            }
        }
    }

    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = []): void
    {
        foreach ($sorting as $property => $order) {
            if (!in_array($property, array_merge($this->_class->getAssociationNames(), $this->_class->getFieldNames()), true)) {
                continue;
            }

            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
    }

    protected function getPropertyName(string $name): string
    {
        if (false === strpos($name, '.')) {
            return 'o.' . $name;
        }

        return $name;
    }
}
