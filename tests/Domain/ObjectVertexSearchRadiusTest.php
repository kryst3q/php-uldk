<?php

namespace Kryst3q\PhpUldk\Tests\Domain;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\ObjectVertexSearchRadius;

class ObjectVertexSearchRadiusTest extends Unit
{
    public function testReturningValue(): void
    {
        $radius = new ObjectVertexSearchRadius(1);

        self::assertSame(1, $radius->getValue());
        self::assertSame('1', (string)$radius);
    }
}
