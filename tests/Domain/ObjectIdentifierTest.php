<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests\Domain;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\ObjectIdentifier;

class ObjectIdentifierTest extends Unit
{
    public function testSuccessfullyConstructIdentifier(): void
    {
        $value = '141201_1.0001.6509';
        $identifier = new ObjectIdentifier($value);

        self::assertSame($value, (string)$identifier);
    }
}

