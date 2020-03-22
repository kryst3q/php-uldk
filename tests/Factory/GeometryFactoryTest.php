<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests\Factory;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\ObjectIdentifier;
use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Domain\RequestName;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\Factory\GeometryFactory;
use Kryst3q\PhpUldk\Model\Geometry;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;

class GeometryFactoryTest extends Unit
{
    private GeometryFactory $factory;

    protected function _before()
    {
        parent::_before();

        $this->factory = new GeometryFactory();
    }

    public function testCreateFromObjectData(): void
    {
        $id = '141201_1.0001.6509';
        $geometryFormat = new GeometryFormat(GeometryFormat::FORMAT_WKT);
        $query = new Query([
            new RequestName(RequestName::GET_PARCEL_BY_ID),
            new ObjectIdentifier($id),
            (new ResponseContentOptions())
                ->requestVoivodeshipName()
                ->requestBoundaryBox()
                ->requestCountyName()
                ->requestCommuneName()
                ->requestObjectIdentifier()
                ->requestParcelNumber()
                ->requestRegionNameOrNumber()
                ->setGeometryFormat($geometryFormat)
        ]);
        $resultArray = explode('|', 'SRID=2180;POLYGON((677047.4 481558.91,677009.54 481577.8,677033.52 481618.26,677045.44 481625.09,677062.41 481626.24,677076.78 481627.23,677082.74 481626.02,677047.4 481558.91))|Mazowieckie|677009.54,481558.91,677082.74,481627.23|Miński|Mińsk Mazowiecki|141201_1.0001.6509|6509|Mińsk Mazowiecki');

        $geometry = $this->factory->createFromObjectData($resultArray, $query);

        self::assertInstanceOf(Geometry::class, $geometry);
        self::assertSame('2180', $geometry->getCoordinateSystem()->getValue());
        self::assertSame('geom_wkt', $geometry->getFormat()->getValue());
        self::assertSame('POLYGON', $geometry->getType()->getValue());
        self::assertSame(
            'SRID=2180;POLYGON((677047.4 481558.91,677009.54 481577.8,677033.52 481618.26,677045.44 481625.09,677062.41 481626.24,677076.78 481627.23,677082.74 481626.02,677047.4 481558.91))',
            $geometry->getGeometry()
        );
    }
}
