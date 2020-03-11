<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests\Domain;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\ObjectCoordinates;
use Kryst3q\PhpUldk\Domain\CoordinateSystem;

class ObjectCoordinatesTest extends Unit
{
    public function testParsingToStringWithCoordinateSystemSet(): void
    {
        $objectCoordinates = new ObjectCoordinates(
            460166.4,
            313380.5,
            new CoordinateSystem(CoordinateSystem::SRID_4326)
        );

        self::assertSame('460166.4,313380.5,4326', (string)$objectCoordinates);
    }

    public function testParsingToStringWithoutCoordinateSystemSet(): void
    {
        $objectCoordinates = new ObjectCoordinates(460166.4, 313380.5);

        self::assertSame('460166.4,313380.5', (string)$objectCoordinates);
    }
}
