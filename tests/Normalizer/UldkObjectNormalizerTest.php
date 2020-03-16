<?php

namespace Kryst3q\PhpUldk\Tests\Normalizer;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\CoordinateSystem;
use Kryst3q\PhpUldk\Domain\ObjectIdentifier;
use Kryst3q\PhpUldk\Domain\ObjectIdentifierCollection;
use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Domain\RequestName;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\Factory\BoundingBoxFactory;
use Kryst3q\PhpUldk\Factory\GeometryFactory;
use Kryst3q\PhpUldk\Model\BoundingBox;
use Kryst3q\PhpUldk\Model\Geometry;
use Kryst3q\PhpUldk\Model\UldkObject;
use Kryst3q\PhpUldk\Normalizer\UldkObjectNormalizer;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;
use Kryst3q\PhpUldk\ValueObject\GeometryType;
use Prophecy\Prophecy\ObjectProphecy;

class UldkObjectNormalizerTest extends Unit
{
    /**
     * @var GeometryFactory|ObjectProphecy
     */
    private ObjectProphecy $geometryFactory;

    /**
     * @var BoundingBoxFactory|ObjectProphecy
     */
    private ObjectProphecy $boundingBoxFactory;

    private UldkObjectNormalizer $normalizer;

    protected function _before()
    {
        parent::_before();

        $this->geometryFactory = $this->prophesize(GeometryFactory::class);
        $this->boundingBoxFactory = $this->prophesize(BoundingBoxFactory::class);
        $this->normalizer = new UldkObjectNormalizer(
            $this->geometryFactory->reveal(),
            $this->boundingBoxFactory->reveal()
        );
    }

    public function testNormalizeGetParcelByIdWktResponse(): void
    {
        $id = '141201_1.0001.6509';
        $bboxString = '677009.54,481558.91,677082.74,481627.23';
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
        $resultString = "Mazowieckie|677009.54,481558.91,677082.74,481627.23|Miński|Mińsk Mazowiecki|141201_1.0001.6509|6509|Mińsk Mazowiecki|SRID=2180;POLYGON((677047.4 481558.91,677009.54 481577.8,677033.52 481618.26,677045.44 481625.09,677062.41 481626.24,677076.78 481627.23,677082.74 481626.02,677047.4 481558.91))";

        $this->boundingBoxFactory
            ->createFromString($bboxString)
            ->shouldBeCalledOnce()
            ->willReturn(
                new BoundingBox(
                    677009.54,
                    481558.91,
                    677082.74,
                    481627.23
                )
            );

        $this->geometryFactory
            ->createFromObjectData(explode('|', $resultString), $query)
            ->shouldBeCalledOnce()
            ->willReturn(
                new Geometry(
                    new CoordinateSystem(CoordinateSystem::SRID_2180),
                    'SRID=2180;POLYGON((677047.4 481558.91,677009.54 481577.8,677033.52 481618.26,677045.44 481625.09,677062.41 481626.24,677076.78 481627.23,677082.74 481626.02,677047.4 481558.91))',
                    new GeometryType(GeometryType::TYPE_POLYGON),
                    $geometryFormat
                )
            );

        $uldkObject = $this->normalizer->denormalize($resultString, $query);

        self::assertInstanceOf(UldkObject::class, $uldkObject);
        self::assertSame($id, $uldkObject->getIdentifier());
        self::assertSame('Mazowieckie', $uldkObject->getVoivodeshipName());
        self::assertSame('Miński', $uldkObject->getCountyName());
        self::assertSame('Mińsk Mazowiecki', $uldkObject->getCommuneName());
        self::assertSame('6509', $uldkObject->getParcelNameOrNumber());
        self::assertSame('Mińsk Mazowiecki', $uldkObject->getRegionName());

        $bbox = $uldkObject->getBoundingBox();
        self::assertInstanceOf(BoundingBox::class, $bbox);
        self::assertSame($bboxString, $bbox->getValue());

        $geometry = $uldkObject->getGeometry();
        self::assertInstanceOf(Geometry::class, $geometry);
        self::assertSame('2180', $geometry->getCoordinateSystem()->getValue());
        self::assertSame('geom_wkt', $geometry->getFormat()->getValue());
        self::assertSame('POLYGON', $geometry->getType()->getValue());
        self::assertSame(
            'SRID=2180;POLYGON((677047.4 481558.91,677009.54 481577.8,677033.52 481618.26,677045.44 481625.09,677062.41 481626.24,677076.78 481627.23,677082.74 481626.02,677047.4 481558.91))',
            $geometry->getGeometry()
        );
    }

    public function testNormalizeGetAggregateAreaWktResponse(): void
    {
        $firstId = '141201_1.0001.6509';
        $secondId = '131201_1.0002.7011';
        $geometryFormat = new GeometryFormat(GeometryFormat::FORMAT_WKT);
        $query = new Query([
            new RequestName(RequestName::GET_PARCEL_BY_ID),
            new ObjectIdentifierCollection([new ObjectIdentifier($firstId), new ObjectIdentifier($secondId)]),
            (new ResponseContentOptions())->setGeometryFormat($geometryFormat)
        ]);
        $resultString = 'POLYGON((676549.8 482287.29,676550.06 482283.87,676539.14 482282.82,676536.23 482319.66,676547.16 482320.68,676549.8 482287.29))';

        $this->geometryFactory
            ->createFromObjectData(explode('|', $resultString), $query)
            ->shouldBeCalledOnce()
            ->willReturn(
                new Geometry(
                    new CoordinateSystem(CoordinateSystem::SRID_2180),
                    $resultString,
                    new GeometryType(GeometryType::TYPE_POLYGON),
                   $geometryFormat
                )
            );

        $uldkObject = $this->normalizer->denormalize($resultString, $query);

        self::assertInstanceOf(UldkObject::class, $uldkObject);
        self::assertNull($uldkObject->getIdentifier());
        self::assertNull($uldkObject->getVoivodeshipName());
        self::assertNull($uldkObject->getCountyName());
        self::assertNull($uldkObject->getCommuneName());
        self::assertNull($uldkObject->getParcelNameOrNumber());
        self::assertNull($uldkObject->getRegionName());
        self::assertNull($uldkObject->getBoundingBox());

        $geometry = $uldkObject->getGeometry();
        self::assertInstanceOf(Geometry::class, $geometry);
        self::assertSame('2180', $geometry->getCoordinateSystem()->getValue());
        self::assertSame('geom_wkt', $geometry->getFormat()->getValue());
        self::assertSame('POLYGON', $geometry->getType()->getValue());
        self::assertSame($resultString, $geometry->getGeometry());
    }
}
