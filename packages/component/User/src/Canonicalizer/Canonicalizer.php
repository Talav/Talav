<?php

declare(strict_types=1);

namespace Talav\Component\User\Canonicalizer;

final class Canonicalizer implements CanonicalizerInterface
{
    public function canonicalize(?string $string): ?string
    {
        return null === $string ? null : mb_convert_case($string, \MB_CASE_LOWER, mb_detect_encoding($string));
    }
}
