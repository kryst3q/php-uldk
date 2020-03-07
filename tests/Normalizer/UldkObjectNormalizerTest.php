<?php

namespace Kryst3q\PhpUldk\Tests\Normalizer;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\ObjectIdentifier;
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
        $query = new Query(
            new RequestName(RequestName::GET_PARCEL_BY_ID),
            new ObjectIdentifier($id),
            (new ResponseContentOptions())
                ->addVoivodeshipName()
                ->addBoundaryBox()
                ->addCountyName()
                ->addCommuneName()
                ->addObjectIdentifier()
                ->addParcelNumber()
                ->addRegionNameOrNumber()
                ->setGeometryFormat($geometryFormat)
        );
        $resultString = "0\nMazowieckie|677009.54,481558.91,677082.74,481627.23|Miński|Mińsk Mazowiecki|141201_1.0001.6509|6509|Mińsk Mazowiecki|SRID=2180;POLYGON((677047.4 481558.91,677009.54 481577.8,677033.52 481618.26,677045.44 481625.09,677062.41 481626.24,677076.78 481627.23,677082.74 481626.02,677047.4 481558.91))\n";

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
            'POLYGON((677047.4 481558.91,677009.54 481577.8,677033.52 481618.26,677045.44 481625.09,677062.41 481626.24,677076.78 481627.23,677082.74 481626.02,677047.4 481558.91))',
            $geometry->getGeometry()
        );
    }

    public function testNormalizeGetParcelByIdOrNrWktResponse(): void
    {
        $resultString = "2\n022503_5.0003.134|134|Zgorzelecki|SRID=2180;POLYGON((216936.780566 358211.157314,216905.017714 358178.821458,216916.431762 358172.059717,216969.822756 358235.328759,217054.157538 358339.824309,217032.156395 358322.48633,216936.780566 358211.157314))\n301903_2.0006.134|134|Pilski|SRID=2180;POLYGON((357143.590312 582012.187165,357171.189869 581990.982699,357198.487619 581969.659709,357231.343201 582019.197403,357278.541769 582090.443646,357323.134739 582150.102651,357227.070646 582199.478226,357144.395303 582013.873562,357143.590312 582012.187165))\n";
    }
}
