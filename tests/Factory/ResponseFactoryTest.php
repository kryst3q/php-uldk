<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests\Factory;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Client\HttpResponse;
use Kryst3q\PhpUldk\Client\Response;
use Kryst3q\PhpUldk\Domain\CoordinateSystem;
use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Domain\RequestName;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\Exception\UldkRequestException;
use Kryst3q\PhpUldk\Factory\ResponseFactory;
use Kryst3q\PhpUldk\Model\Geometry;
use Kryst3q\PhpUldk\Model\UldkObject;
use Kryst3q\PhpUldk\Model\UldkObjectCollection;
use Kryst3q\PhpUldk\Normalizer\UldkObjectNormalizer;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;
use Kryst3q\PhpUldk\ValueObject\GeometryType;
use Prophecy\Prophecy\ObjectProphecy;

class ResponseFactoryTest extends Unit
{
    /**
     * @var ObjectProphecy|UldkObjectNormalizer
     */
    private ObjectProphecy $uldkObjectNormalizer;

    private ResponseFactory $factory;

    protected function _before()
    {
        parent::_before();

        $this->uldkObjectNormalizer = $this->prophesize(UldkObjectNormalizer::class);
        $this->factory = new ResponseFactory($this->uldkObjectNormalizer->reveal());
    }

    public function testThrowExceptionIfErrorOccurredOnUldkServiceSide(): void
    {
        $this->expectException(UldkRequestException::class);
        $this->expectExceptionMessage('An error occurred during execution of ULDK request: wystąpił bład.');

        $this->factory->create("-1 wystąpił bład\n", new Query([]));
    }

    public function testCreateSnapToPointWktResponse(): void
    {
        $requestResult = "0\nPOINT(482205.818679214 673473.221671305)\n0.848159078670161\n";
        $query = new Query([
            new RequestName(RequestName::SNAP_TO_POINT)
        ]);
        $geometry = new Geometry(
            new CoordinateSystem(CoordinateSystem::SRID_2180),
            'POINT(482205.818679214 673473.221671305)',
            new GeometryType(GeometryType::TYPE_POINT),
            new GeometryFormat(GeometryFormat::FORMAT_WKT)
        );

        $this->uldkObjectNormalizer
            ->denormalize('POINT(482205.818679214 673473.221671305)', $query)
            ->shouldBeCalledOnce()
            ->willReturn(new UldkObject($geometry));

        $response = $this->factory->create($requestResult, $query);

        self::assertInstanceOf(Response::class, $response);
        self::assertInstanceOf(HttpResponse::class, $response);

        self::assertSame($query, $response->getQuery());

        $uldkObjectCollection = $response->getObjects();
        self::assertInstanceOf(UldkObjectCollection::class, $uldkObjectCollection);

        $uldkObject = $uldkObjectCollection->getFirst();
        self::assertInstanceOf(UldkObject::class, $uldkObject);
        self::assertSame($geometry, $uldkObject->getGeometry());
        self::assertSame(0.848159078670161, $uldkObject->getDistanceToSnappedPointInMeters());
        self::assertNull($uldkObject->getBoundingBox());
        self::assertNull($uldkObject->getCommuneName());
        self::assertNull($uldkObject->getCountyName());
        self::assertNull($uldkObject->getIdentifier());
        self::assertNull($uldkObject->getRegionName());
        self::assertNull($uldkObject->getParcelNameOrNumber());
        self::assertNull($uldkObject->getVoivodeshipName());
    }

    public function testCreateGetParcelByIdOrNrWktResponse(): void
    {
        $requestResponse = "2\nSRID=2180;POLYGON((216936.780566 358211.157314,216905.017714 358178.821458,216916.431762 358172.059717,216969.822756 358235.328759,217054.157538 358339.824309,217032.156395 358322.48633,216936.780566 358211.157314))|Bogatynia|Krzewina\nSRID=2180;POLYGON((357143.590312 582012.187165,357171.189869 581990.982699,357198.487619 581969.659709,357231.343201 582019.197403,357278.541769 582090.443646,357323.134739 582150.102651,357227.070646 582199.478226,357144.395303 582013.873562,357143.590312 582012.187165))|Kaczory|Krzewina\n";
        $query = new Query([
            new RequestName(RequestName::GET_PARCEL_BY_ID_OR_NR),
            (new ResponseContentOptions())
                ->setGeometryFormat(new GeometryFormat(GeometryFormat::FORMAT_WKT))
                ->addCommuneName()
                ->addRegionNameOrNumber()
        ]);

        $result1 = 'SRID=2180;POLYGON((216936.780566 358211.157314,216905.017714 358178.821458,216916.431762 358172.059717,216969.822756 358235.328759,217054.157538 358339.824309,217032.156395 358322.48633,216936.780566 358211.157314))|Bogatynia|Krzewina';
        $geometry1 = new Geometry(
            new CoordinateSystem(CoordinateSystem::SRID_2180),
            $result1,
            new GeometryType(GeometryType::TYPE_POLYGON),
            new GeometryFormat(GeometryFormat::FORMAT_WKT)
        );
        $uldkObject1 = (new UldkObject($geometry1))
            ->setCommuneName('Bogatynia')
            ->setRegionName('Krzewina');
        $this->uldkObjectNormalizer
            ->denormalize($result1, $query)
            ->shouldBeCalledOnce()
            ->willReturn($uldkObject1);

        $result2 = 'SRID=2180;POLYGON((357143.590312 582012.187165,357171.189869 581990.982699,357198.487619 581969.659709,357231.343201 582019.197403,357278.541769 582090.443646,357323.134739 582150.102651,357227.070646 582199.478226,357144.395303 582013.873562,357143.590312 582012.187165))|Kaczory|Krzewina';
        $geometry2 = new Geometry(
            new CoordinateSystem(CoordinateSystem::SRID_2180),
            $result2,
            new GeometryType(GeometryType::TYPE_POLYGON),
            new GeometryFormat(GeometryFormat::FORMAT_WKT)
        );
        $uldkObject2 = (new UldkObject($geometry2))
            ->setCommuneName('Kaczory')
            ->setRegionName('Krzewina');
        $this->uldkObjectNormalizer
            ->denormalize($result2, $query)
            ->shouldBeCalledOnce()
            ->willReturn($uldkObject2);

        $expectedCollectionObjects = [
            $uldkObject1,
            $uldkObject2
        ];

        $response = $this->factory->create($requestResponse, $query);

        self::assertInstanceOf(Response::class, $response);
        self::assertInstanceOf(HttpResponse::class, $response);

        self::assertSame($query, $response->getQuery());

        $uldkObjectCollection = $response->getObjects();
        self::assertInstanceOf(UldkObjectCollection::class, $uldkObjectCollection);

        self::assertEquals($expectedCollectionObjects, $uldkObjectCollection->getIterator()->getArrayCopy());
    }
}
