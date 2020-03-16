<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Client\HttpRequest;
use Kryst3q\PhpUldk\Client\Response;
use Kryst3q\PhpUldk\Domain\CoordinateSystem;
use Kryst3q\PhpUldk\Domain\ObjectCoordinates;
use Kryst3q\PhpUldk\Domain\ObjectIdentifier;
use Kryst3q\PhpUldk\Domain\ObjectIdentifierCollection;
use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Domain\RequestName;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\Model\Geometry;
use Kryst3q\PhpUldk\Model\UldkObject;
use Kryst3q\PhpUldk\Model\UldkObjectCollection;
use Kryst3q\PhpUldk\PhpUldk;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;
use Kryst3q\PhpUldk\ValueObject\GeometryType;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PhpUldkTest extends Unit
{
    /**
     * @var ObjectProphecy|HttpRequest
     */
    private ObjectProphecy $httpRequest;

    /**
     * @var ObjectProphecy|ContainerBuilder
     */
    private ObjectProphecy $container;

    private ResponseContentOptions $options;

    private PhpUldk $phpUldk;

    protected function _before()
    {
        parent::_before();

        $this->httpRequest = $this->prophesize(HttpRequest::class);
        $this->container = $this->prophesize(ContainerBuilder::class);
        $this->container
            ->get('request')
            ->shouldBeCalledOnce()
            ->willReturn($this->httpRequest->reveal());
        $this->options = (new ResponseContentOptions())
            ->setGeometryFormat(new GeometryFormat(GeometryFormat::FORMAT_WKT));
        $this->phpUldk = new PhpUldk($this->options, $this->container->reveal());
    }

    /**
     * @dataProvider getObjectByDataProvider
     */
    public function testGetObjectBy(string $requestName, string $methodName, $argument): void
    {
        $object = new UldkObject(new Geometry(
            new CoordinateSystem(CoordinateSystem::SRID_2180),
            'POINT(482205.818679214 673473.221671305)',
            new GeometryType(GeometryType::TYPE_POINT),
            new GeometryFormat(GeometryFormat::FORMAT_WKT)
        ));
        $collection = new UldkObjectCollection();
        $collection->add($object);
        $query = new Query([
            new RequestName($requestName),
            $this->options
        ]);
        $this->httpRequest
            ->execute(Argument::type(Query::class))
            ->shouldBeCalledOnce()
            ->willReturn(new Response($collection, $query));

        $actual = call_user_func([$this->phpUldk, $methodName], $argument);

        if ($requestName === RequestName::GET_PARCEL_BY_ID_OR_NR) {
            self::assertSame($collection, $actual);
        } else {
            self::assertSame($object, $actual);
        }
    }

    public function getObjectByDataProvider(): array
    {
        $identifier = new ObjectIdentifier('1234567890');
        $identifierCollection = new ObjectIdentifierCollection([$identifier, $identifier]);
        $coordinates = new ObjectCoordinates(460166.4, 313380.5);

        return [
            [RequestName::GET_PARCEL_BY_ID, 'getParcelById', $identifier],
            [RequestName::GET_PARCEL_BY_ID_OR_NR, 'getParcelByIdOrNr', $identifier],
            [RequestName::GET_REGION_BY_ID, 'getRegionById', $identifier],
            [RequestName::GET_COMMUNE_BY_ID, 'getCommuneById', $identifier],
            [RequestName::GET_COUNTY_BY_ID, 'getCountyById', $identifier],
            [RequestName::GET_VOIVODESHIP_BY_ID, 'getVoivodeshipById', $identifier],
            [RequestName::GET_AGGREGATE_AREA, 'getAggregateArea', $identifierCollection],
            [RequestName::SNAP_TO_POINT, 'snapToPoint', $coordinates],
            [RequestName::GET_PARCEL_BY_COORDINATES, 'getParcelByCoordinates', $coordinates],
            [RequestName::GET_REGION_BY_COORDINATES, 'getRegionByCoordinates', $coordinates],
            [RequestName::GET_COMMUNE_BY_COORDINATES, 'getCommuneByCoordinates', $coordinates],
            [RequestName::GET_COUNTY_BY_COORDINATES, 'getCountyByCoordinates', $coordinates],
            [RequestName::GET_VOIVODESHIP_BY_COORDINATES, 'getVoivodeshipByCoordinates', $coordinates],
        ];
    }
}
