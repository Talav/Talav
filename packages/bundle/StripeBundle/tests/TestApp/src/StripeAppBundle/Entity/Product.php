<?php

declare(strict_types=1);

namespace StripeAppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Talav\StripeBundle\Entity\Product as BaseProduct;

#[Entity]
#[Table(name: 'test_stripe_product')]
class Product extends BaseProduct
{
    #[Id]
    #[Column(type: 'string')]
    protected mixed $id = null;
}
