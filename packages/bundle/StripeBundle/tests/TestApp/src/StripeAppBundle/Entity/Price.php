<?php

declare(strict_types=1);

namespace StripeAppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Talav\StripeBundle\Entity\Price as BasePrice;

#[Entity]
#[Table(name: 'test_stripe_price')]
class Price extends BasePrice
{
    #[Id]
    #[Column(type: 'string')]
    protected mixed $id = null;
}
