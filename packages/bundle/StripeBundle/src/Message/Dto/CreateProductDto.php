<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Message\Dto;

class CreateProductDto implements ModelDto
{
    public function __construct(
        public string $name,
        public bool $active = false,
        public array $images = [],
        public bool $livemode = false,
        public array $metadata = [],
        public array $packageDimensions = [],
        public bool $shippable = false,
        public mixed $id = null,
        public ?string $description = null,
        public ?string $statementDescriptor = null,
        public ?string $taxCode = null,
        public ?string $unitLabel = null,
        public ?int $updated = null,
        public ?string $url = null,
    ) {
    }
}
