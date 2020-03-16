<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests\Domain;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\CoordinateSystem;
use Kryst3q\PhpUldk\Domain\ObjectCoordinates;
use Kryst3q\PhpUldk\Domain\ObjectIdentifier;
use Kryst3q\PhpUldk\Domain\ObjectVertexSearchRadius;
use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Domain\RequestName;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;

class QueryTest extends Unit
{
    /**
     * @dataProvider correctlySerializeQueryDataProvider
     */
    public function testCorrectlySerializeQuery(string $expected, array $elements): void
    {
        $query = new Query($elements);
        self::assertSame($expected, urldecode((string)$query));
    }

    public function correctlySerializeQueryDataProvider(): array
    {
        return [
            [
                '?request=GetParcelById&id=141201_1.0001.6509&result=geom_extent,geom_wkt&srid=4326',
                [
                    new RequestName(RequestName::GET_PARCEL_BY_ID),
                    new ObjectIdentifier('141201_1.0001.6509'),
                    (new ResponseContentOptions())
                        ->requestBoundaryBox()
                        ->setGeometryFormat(new GeometryFormat(GeometryFormat::FORMAT_WKT)),
                    new CoordinateSystem(CoordinateSystem::SRID_4326)
                ]
            ],
            [
                '?request=GetParcelByIdOrNr&id=Krzewina 134&result=teryt,commune,parcel,geom_wkb',
                [
                    new RequestName(RequestName::GET_PARCEL_BY_ID_OR_NR),
                    new ObjectIdentifier('Krzewina 134'),
                    (new ResponseContentOptions())
                        ->requestObjectIdentifier()
                        ->requestCommuneName()
                        ->requestParcelNumber()
                        ->setGeometryFormat(new GeometryFormat(GeometryFormat::FORMAT_WKB))
                ]
            ],
            [
                '?request=GetParcelByXY&xy=460166.4,313380.5&srid=2180',
                [
                    new RequestName(RequestName::GET_PARCEL_BY_COORDINATES),
                    new ObjectCoordinates(460166.4, 313380.5),
                    new CoordinateSystem(CoordinateSystem::SRID_2180)
                ]
            ],
            [
                '?request=GetParcelByXY&xy=460166.4,313380.5,4326',
                [
                    new RequestName(RequestName::GET_PARCEL_BY_COORDINATES),
                    new ObjectCoordinates(460166.4, 313380.5, new CoordinateSystem(CoordinateSystem::SRID_4326))
                ]
            ],
            [
                '?request=SnapToPoint&xy=482205,673473&radius=12',
                [
                    new RequestName(RequestName::SNAP_TO_POINT),
                    new ObjectCoordinates(482205.0, 673473.0),
                    new ObjectVertexSearchRadius(12)
                ]
            ],
            [
                '?request=GetAggregateArea&id=141201_1.0001.3912,141201_1.0001.39138',
                [
                    new RequestName(RequestName::GET_AGGREGATE_AREA),
                    new ObjectIdentifier('141201_1.0001.3912,141201_1.0001.39138')
                ]
            ]
        ];
    }
}
