<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests\Domain;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\ObjectCoordinates;
use Kryst3q\PhpUldk\Domain\ObjectIdentifier;
use Kryst3q\PhpUldk\Domain\ObjectVertexSearchRadius;
use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Domain\RequestName;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\ValueObject\CoordinateSystem;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;

class QueryTest extends Unit
{
    public function testReturnNullIftestReturnResponseContentOptionsIfWasSetWasNotSet(): void
    {
        $query = new Query(
            new RequestName(RequestName::GET_PARCEL_BY_COORDINATES),
            null,
            null,
            new ObjectCoordinates(460166.4, 313380.5, new CoordinateSystem(CoordinateSystem::SRID_4326)),
            null
        );

        self::assertNull($query->getResponseContentOptions());
    }

    public function testReturnResponseContentOptionsIfWasSet(): void
    {
        $query = new Query(
            new RequestName(RequestName::GET_PARCEL_BY_ID_OR_NR),
            new ObjectIdentifier('Krzewina 134'),
            (new ResponseContentOptions())
                ->addObjectIdentifier()
                ->addCommuneName()
                ->addParcelNumber()
                ->setGeometryFormat(new GeometryFormat(GeometryFormat::FORMAT_WKB)),
            null,
            null
        );

        self::assertInstanceOf(ResponseContentOptions::class, $query->getResponseContentOptions());
    }

    /**
     * @dataProvider correctlySerializeQueryDataProvider
     */
    public function testCorrectlySerializeQuery(
        string $expected,
        RequestName $request,
        ?ObjectIdentifier $id,
        ?ResponseContentOptions $result,
        ?ObjectCoordinates $xy,
        ?ObjectVertexSearchRadius $radius
    ): void {
        $query = new Query($request, $id, $result, $xy, $radius);
        self::assertSame($expected, urldecode((string)$query));
    }

    public function correctlySerializeQueryDataProvider(): array
    {
        return [
            [
                '?request=GetParcelById&id=141201_1.0001.6509&result=geom_extent,geom_wkt',
                new RequestName(RequestName::GET_PARCEL_BY_ID),
                new ObjectIdentifier('141201_1.0001.6509'),
                (new ResponseContentOptions())
                    ->addBoundaryBox()
                    ->setGeometryFormat(new GeometryFormat(GeometryFormat::FORMAT_WKT)),
                null,
                null
            ],
            [
                '?request=GetParcelByIdOrNr&id=Krzewina 134&result=teryt,commune,parcel,geom_wkb',
                new RequestName(RequestName::GET_PARCEL_BY_ID_OR_NR),
                new ObjectIdentifier('Krzewina 134'),
                (new ResponseContentOptions())
                    ->addObjectIdentifier()
                    ->addCommuneName()
                    ->addParcelNumber()
                    ->setGeometryFormat(new GeometryFormat(GeometryFormat::FORMAT_WKB)),
                null,
                null
            ],
            [
                '?request=GetParcelByXY&xy=460166.4,313380.5',
                new RequestName(RequestName::GET_PARCEL_BY_COORDINATES),
                null,
                null,
                new ObjectCoordinates(460166.4, 313380.5),
                null
            ],
            [
                '?request=GetParcelByXY&xy=460166.4,313380.5,4326',
                new RequestName(RequestName::GET_PARCEL_BY_COORDINATES),
                null,
                null,
                new ObjectCoordinates(460166.4, 313380.5, new CoordinateSystem(CoordinateSystem::SRID_4326)),
                null
            ],
            [
                '?request=SnapToPoint&xy=482205,673473&radius=12',
                new RequestName(RequestName::SNAP_TO_POINT),
                null,
                null,
                new ObjectCoordinates(482205.0, 673473.0),
                new ObjectVertexSearchRadius(12)
            ],
            [
                '?request=GetAggregateArea&id=141201_1.0001.3912,141201_1.0001.39138',
                new RequestName(RequestName::GET_AGGREGATE_AREA),
                new ObjectIdentifier('141201_1.0001.3912,141201_1.0001.39138'),
                null,
                null,
                null
            ]
        ];
    }
}
