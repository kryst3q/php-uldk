<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk;

use Kryst3q\PhpUldk\Client\HttpRequest;
use Kryst3q\PhpUldk\Client\HttpResponse;
use Kryst3q\PhpUldk\Domain\ObjectCoordinates;
use Kryst3q\PhpUldk\Domain\ObjectIdentifier;
use Kryst3q\PhpUldk\Domain\ObjectIdentifierCollection;
use Kryst3q\PhpUldk\Domain\ObjectVertexSearchRadius;
use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Domain\RequestName;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\Exception\UldkRequestException;
use Kryst3q\PhpUldk\Model\UldkObject;
use Kryst3q\PhpUldk\Model\UldkObjectCollection;
use Kryst3q\PhpUldk\Provider\ServiceProvider;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PhpUldk
{
    private ResponseContentOptions $defaultOptions;

    private HttpRequest $httpRequest;

    public function __construct(ResponseContentOptions $options = null, ContainerBuilder $container = null)
    {
        $container = $container ?? ServiceProvider::buildContainer();

        $this->httpRequest = $container->get('request');
        $this->defaultOptions = $options ?? new ResponseContentOptions();
    }

    /**
     * @throws UldkRequestException
     */
    public function getParcelById(ObjectIdentifier $parcelId, ResponseContentOptions $options = null): UldkObject
    {
        return $this->getObjectById(
            new RequestName(RequestName::GET_PARCEL_BY_ID),
            $parcelId,
            $options
        );
    }

    /**
     * @throws UldkRequestException
     */
    public function getRegionById(ObjectIdentifier $regionId, ResponseContentOptions $options = null): UldkObject
    {
        return $this->getObjectById(
            new RequestName(RequestName::GET_REGION_BY_ID),
            $regionId,
            $options
        );
    }

    /**
     * @throws UldkRequestException
     */
    public function getCommuneById(ObjectIdentifier $communeId, ResponseContentOptions $options = null): UldkObject
    {
        return $this->getObjectById(
            new RequestName(RequestName::GET_COMMUNE_BY_ID),
            $communeId,
            $options
        );
    }

    /**
     * @throws UldkRequestException
     */
    public function getCountyById(ObjectIdentifier $countyId, ResponseContentOptions $options = null): UldkObject
    {
        return $this->getObjectById(
            new RequestName(RequestName::GET_COMMUNE_BY_ID),
            $countyId,
            $options
        );
    }

    /**
     * @throws UldkRequestException
     */
    public function getVoivodeshipById(ObjectIdentifier $voivodeshipId, ResponseContentOptions $options = null): UldkObject
    {
        return $this->getObjectById(
            new RequestName(RequestName::GET_COMMUNE_BY_ID),
            $voivodeshipId,
            $options
        );
    }

    /**
     * @throws UldkRequestException
     */
    public function getParcelByIdOrNr(
        ObjectIdentifier $parcelIdOrNr,
        ResponseContentOptions $options = null
    ): UldkObjectCollection {
        $query = new Query([
            new RequestName(RequestName::GET_PARCEL_BY_ID_OR_NR),
            $parcelIdOrNr,
            $options
        ]);

        $response = $this->makeRequest($query);

        return $response->getObjects();
    }

    /**
     * @throws UldkRequestException
     */
    public function getParcelByCoordinates(
        ObjectCoordinates $coordinates,
        ResponseContentOptions $options = null
    ): UldkObject {
        return $this->getObjectByCoordinates(
            new RequestName(RequestName::GET_PARCEL_BY_COORDINATES),
            $coordinates,
            $options
        );
    }

    /**
     * @throws UldkRequestException
     */
    public function getRegionByCoordinates(
        ObjectCoordinates $coordinates,
        ResponseContentOptions $options = null
    ): UldkObject {
        return $this->getObjectByCoordinates(
            new RequestName(RequestName::GET_REGION_BY_COORDINATES),
            $coordinates,
            $options
        );
    }

    /**
     * @throws UldkRequestException
     */
    public function getCommuneByCoordinates(
        ObjectCoordinates $coordinates,
        ResponseContentOptions $options = null
    ): UldkObject {
        return $this->getObjectByCoordinates(
            new RequestName(RequestName::GET_COMMUNE_BY_COORDINATES),
            $coordinates,
            $options
        );
    }

    /**
     * @throws UldkRequestException
     */
    public function getCountyByCoordinates(
        ObjectCoordinates $coordinates,
        ResponseContentOptions $options = null
    ): UldkObject {
        return $this->getObjectByCoordinates(
            new RequestName(RequestName::GET_COUNTY_BY_COORDINATES),
            $coordinates,
            $options
        );
    }

    /**
     * @throws UldkRequestException
     */
    public function getVoivodeshipByCoordinates(
        ObjectCoordinates $coordinates,
        ResponseContentOptions $options = null
    ): UldkObject {
        return $this->getObjectByCoordinates(
            new RequestName(RequestName::GET_VOIVODESHIP_BY_COORDINATES),
            $coordinates,
            $options
        );
    }

    /**
     * @throws UldkRequestException
     */
    public function snapToPoint(
        ObjectCoordinates $coordinates,
        ObjectVertexSearchRadius $searchRadius = null,
        GeometryFormat $geometryFormat = null
    ): UldkObject {
        $query = new Query([
            new RequestName(RequestName::SNAP_TO_POINT),
            $coordinates,
            $searchRadius
        ]);
        
        if ($geometryFormat !== null) {
            $options = clone $this->defaultOptions;
            $options->setGeometryFormat($geometryFormat);
            
            $query->addElement($options);
        }

        $result = $this->makeRequest($query);

        return $result->getObjects()->getFirst();
    }

    /**
     * @throws UldkRequestException
     */
    public function getAggregateArea(
        ObjectIdentifierCollection $objectIdentifiers,
        GeometryFormat $geometryFormat = null
    ): UldkObject {
        $query = new Query([
            new RequestName(RequestName::GET_AGGREGATE_AREA),
            $objectIdentifiers,
        ]);
        
        if ($geometryFormat !== null) {
            $options = new ResponseContentOptions();
            $options->setGeometryFormat($geometryFormat);
            
            $query->addElement($options);
        }
        
        $result = $this->makeRequest($query);

        return $result->getObjects()->getFirst();
    }

    /**
     * @throws UldkRequestException
     */
    private function getObjectById(
        RequestName $requestName,
        ObjectIdentifier $objectId,
        ResponseContentOptions $options = null
    ): UldkObject {
        $query = new Query([
            $requestName,
            $objectId,
            $options,
        ]);
        $response = $this->makeRequest($query);

        return $response->getObjects()->getFirst();
    }

    /**
     * @throws UldkRequestException
     */
    private function getObjectByCoordinates(
        RequestName $requestName,
        ObjectCoordinates $coordinates,
        ResponseContentOptions $options = null
    ): UldkObject {
        $query = new Query([
            $requestName,
            $coordinates,
            $options
        ]);
        $response = $this->makeRequest($query);

        return $response->getObjects()->getFirst();
    }

    /**
     * @throws UldkRequestException
     */
    private function makeRequest(Query $query): HttpResponse
    {
        $this->addCoordinateSystemToQuery($query);

        return $this->httpRequest->execute($query);
    }

    private function addCoordinateSystemToQuery(Query $query): void
    {
        $hasOptionsSet = $query->hasElement(ResponseContentOptions::ELEMENT_KEY);
        $options = $hasOptionsSet
            ? $query->getElement(ResponseContentOptions::ELEMENT_KEY)
            : clone $this->defaultOptions;

        if (!$hasOptionsSet) {
            $query->addElement($options);
        }

        $query->addElement($options->getRequestedCoordinateSystem());
    }
}
