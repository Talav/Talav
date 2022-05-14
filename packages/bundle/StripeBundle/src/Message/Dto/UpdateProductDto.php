<?php

declare(strict_types=1);

namespace Talav\StripeBundle\Message\Dto;

class UpdateProductDto implements ModelDto
{
    public function __construct(
        public ?string $name = null,
        public ?bool $active = null,
        public ?array $images = null,
        public ?bool $livemode = null,
        public ?array $metadata = null,
        public ?array $packageDimensions = null,
        public ?bool $shippable = null,
        public ?string $description = null,
        public ?string $statementDescriptor = null,
        public ?string $taxCode = null,
        public ?string $unitLabel = null,
        public ?int $updated = null,
        public ?string $url = null,
    ) {
    }
}
