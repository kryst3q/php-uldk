<?php

namespace Kryst3q\PhpUldk\Tests\Domain;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\CoordinateSystem;

class CoordinateSystemTest extends Unit
{
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
