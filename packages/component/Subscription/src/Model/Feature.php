<?php

declare(strict_types=1);

namespace Talav\Component\Subscription\Model;

class Feature
{
    public function __construct(
        // Internal feature name
        public string $name,
        // Disabled features always fail feature check
        public bool $isEnabled = false,
        // Unlimited features always pass feature check
        public bool $isUnlimited = false,
        // Optional max value for the feature
        public ?int $value,
    ) {
    }

    public static function disabled(string $name): Feature
    {
        return new Feature($name, false);
    }

    public static function unlimited(string $name): Feature
    {
        return new Feature($name, true, true);
    }
}
