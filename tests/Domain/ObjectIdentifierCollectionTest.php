<?php

namespace Kryst3q\PhpUldk\Tests\Domain;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\ObjectIdentifier;
use Kryst3q\PhpUldk\Domain\ObjectIdentifierCollection;

class ObjectIdentifierCollectionTest extends Unit
{
    public function testToStringMethod(): void
    {
        $collection = new ObjectIdentifierCollection([
            new ObjectIdentifier('141201_1.0001.3912'),
            new ObjectIdentifier('141201_1.0001.39138')
        ]);

        self::assertSame('141201_1.0001.3912,141201_1.0001.39138', (string) $collection);
    }
}
