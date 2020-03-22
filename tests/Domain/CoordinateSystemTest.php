<?php

namespace Kryst3q\PhpUldk\Tests\Domain;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\CoordinateSystem;
use Kryst3q\PhpUldk\Exception\NotSupportedSridException;

class CoordinateSystemTest extends Unit
{
    public function testThrowExceptionOnUnsupportedCoordinateSystem(): void
    {
        $this->expectException(NotSupportedSridException::class);
        $this->expectExceptionMessage('Spatial reference identifier "1234" is not supported. Supported identifiers: "2180", "4326".');

        new CoordinateSystem('1234');
    }

    public function testGetElementKey()
    {
        $coordinateSystem = new CoordinateSystem(CoordinateSystem::SRID_2180);
        self::assertSame('srid', $coordinateSystem->getElementKey());
    }

    public function testGetSupportedSrids()
    {
        self::assertEquals(
            [
                '2180',
                '4326'
            ],
            CoordinateSystem::getSupportedSrids()
        );
    }
}
