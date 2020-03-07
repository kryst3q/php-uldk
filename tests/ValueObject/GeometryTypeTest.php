<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests\ValueObject;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Exception\NotSupportedGeometryTypeException;
use Kryst3q\PhpUldk\ValueObject\GeometryType;

class GeometryTypeTest extends Unit
{
    /**
     * @dataProvider successfullyConstructDataProvider
     */
    public function testSuccessfullyConstruct(string $geometryType): void
    {
        $instance = new GeometryType($geometryType);

        self::assertSame($geometryType, $instance->getValue());
        self::assertSame($geometryType, (string)$instance);
    }

    public function testThrowAnExceptionIfNotSupportedGeometryTypePassed(): void
    {
        $this->expectException(NotSupportedGeometryTypeException::class);

        new GeometryType('NOTSUPPORTEDTYPE');
    }

    public function successfullyConstructDataProvider(): array
    {
        return [
            ['MULTILINESTRING'],
            ['GEOMETRYCOLLECTION'],
            ['POINT'],
            ['MULTIPOINT'],
            ['MULTIPOLYGON'],
            ['LINESTRING'],
            ['POLYGON'],
        ];
    }
}
