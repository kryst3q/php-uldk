<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests\ValueObject;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\Exception\NotSupportedGeometryFormatException;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;

class GeometryFormatTest extends Unit
{
    /**
     * @dataProvider successfullyConstructDataProvider
     */
    public function testSuccessfullyConstruct(string $value): void
    {
        $geometryFormat = new GeometryFormat($value);
        self::assertSame($value, $geometryFormat->getValue());
    }

    public function testThrowAnExceptionIfNotSupportedGeometryFormatPassed(): void
    {
        $this->expectException(NotSupportedGeometryFormatException::class);

        new ResponseContentOptions(new GeometryFormat('geom_wkk'));
    }

    public function successfullyConstructDataProvider(): array
    {
        return [
            ['geom_wkt'],
            ['geom_wkb']
        ];
    }
}
