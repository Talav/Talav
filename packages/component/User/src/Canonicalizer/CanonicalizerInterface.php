<?php

declare(strict_types=1);

namespace Talav\Component\User\Canonicalizer;

interface CanonicalizerInterface
{
    public function canonicalize(?string $string): ?string;
}
