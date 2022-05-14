<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Message\Command;

use Talav\Component\Resource\Model\DomainEventInterface;
use Talav\StripeBundle\Message\Dto\CreateProductDto;

final class CreateProductCommand implements DomainEventInterface
{
    public function __construct(
        public CreateProductDto $dto
    ) {
    }
}
