<?php

declare(strict_types=1);

namespace Talav\Component\User\Tests\Unit\Canonicalizer;

use PHPUnit\Framework\TestCase;
use Talav\Component\User\Canonicalizer\Canonicalizer;

final class CanonicalizerTest extends TestCase
{
    /**
     * @test
     */
    public function it_canonicalizes_caps_lock()
    {
        $can = new Canonicalizer();
        $this->assertEquals('test', $can->canonicalize('Test'));
    }
}
