<?php

declare(strict_types=1);

namespace StripeAppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Talav\StripeBundle\Entity\Customer as BaseCustomer;

#[Entity]
#[Table(name: 'test_stripe_customer')]
class Customer extends BaseCustomer
{
    #[Id]
    #[Column(type: 'string')]
    protected mixed $id = null;
}
