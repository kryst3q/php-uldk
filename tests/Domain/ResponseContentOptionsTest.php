<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests\Domain;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;

class ResponseContentOptionsTest extends Unit
{
    public function testReturnEmptyStringIfNoOptionsWasAdded(): void
    {
        $options = new ResponseContentOptions();

        self::assertEmpty((string)$options);
    }

    public function testReturnDefaultGeometryFormatIfNoneWasSet(): void
    {
        $options = new ResponseContentOptions();

        self::assertEquals(new GeometryFormat('geom_wkb'), $options->getRequestedGeometryFormat());
    }

    /**
     * @dataProvider returnAllOptionsDataProvider
     */
    public function testReturnAllOptions(string $expected, ?string $geometryFormat): void
    {
        $options = (new ResponseContentOptions())
            ->requestBoundaryBox()
            ->requestCountyName()
            ->requestCommuneName()
            ->requestObjectIdentifier()
            ->requestParcelNumber()
            ->requestRegionNameOrNumber()
            ->requestVoivodeshipName()
        ;

        if ($geometryFormat !== null) {
            $options->setGeometryFormat(new GeometryFormat($geometryFormat));
        }

        self::assertSame($expected, (string)$options);
    }

    public function returnAllOptionsDataProvider(): array
    {
        return [
            ['geom_extent,county,commune,teryt,parcel,region,voivodeship,geom_wkb', 'geom_wkb'],
            ['geom_extent,county,commune,teryt,parcel,region,voivodeship,geom_wkt', 'geom_wkt'],
            ['geom_extent,county,commune,teryt,parcel,region,voivodeship', null],
        ];
    }
}
