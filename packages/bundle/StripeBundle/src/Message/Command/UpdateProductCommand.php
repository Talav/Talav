<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Message\Command;

use Talav\Component\Resource\Model\DomainEventInterface;
use Talav\StripeBundle\Entity\Product;
use Talav\StripeBundle\Message\Dto\UpdateProductDto;

final class UpdateProductCommand implements DomainEventInterface
{
    public function __construct(
        public Product $product,
        public UpdateProductDto $dto
    ) {
    }
}
