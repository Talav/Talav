<?php

namespace Talav\Media\Tests\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Talav\Component\Media\Provider\Constraints;

class ConstraintsTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_list_of_constraints(): void
    {
        $constraints = (new Constraints(['txt']))->getFieldConstraints();
        self::assertCount(2, $constraints);
        self::assertInstanceOf('Symfony\Component\Validator\Constraints\File', $constraints[0]);
        self::assertInstanceOf('Symfony\Component\Validator\Constraints\Callback', $constraints[1]);
    }
}
