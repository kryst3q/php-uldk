<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk;

use Kryst3q\PhpUldk\Client\HttpRequest;
use Kryst3q\PhpUldk\Domain\ObjectCoordinates;
use Kryst3q\PhpUldk\Domain\ObjectIdentifier;
use Kryst3q\PhpUldk\Domain\ObjectIdentifierCollection;
use Kryst3q\PhpUldk\Domain\ObjectVertexSearchRadius;
use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Domain\RequestName;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\Exception\UldkRequestException;
use Kryst3q\PhpUldk\Model\UldkObject;
use Kryst3q\PhpUldk\Normalizer\UldkObjectNormalizer;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;
use Model\UldkObjectCollection;

class PhpUldk
{
    private ResponseContentOptions $defaultOptions;

    private HttpRequest $httpRequest;

    private UldkObjectNormalizer $normalizer;

    public function __construct(HttpRequest $httpRequest, UldkObjectNormalizer $normalizer)
    {
        $this->httpRequest = $httpRequest;
        $this->normalizer = $normalizer;

        $this->defaultOptions = (new ResponseContentOptions())
            ->addBoundaryBox()
            ->setGeometryFormat(new GeometryFormat(GeometryFormat::FORMAT_WKT));
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
            $options ?? $this->defaultOptions,
        ]);

        $result = $this->makeRequest($query);
        $uldkObjectCollection = new UldkObjectCollection();

        foreach ($result as $rawObjectData) {
            $uldkObjectCollection->add($this->normalizer->denormalize($rawObjectData, $query));
        }

        return $uldkObjectCollection;
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
        ObjectVertexSearchRadius $searchRadius,
        GeometryFormat $geometryFormat = null
    ): UldkObject {
        $options = new ResponseContentOptions();

        if ($geometryFormat !== null) {
            $options->setGeometryFormat($geometryFormat);
        }

        $query = new Query([
            RequestName::SNAP_TO_POINT,
            $coordinates,
            $searchRadius,
            $options
        ]);
        $result = $this->makeRequest($query);
        $uldkObject = $this->normalizer->denormalize($result[0], $query);
        $uldkObject->setDistanceToSnappedPointInMeters((float) $result[1]);

        return $uldkObject;
    }

    /**
     * @throws UldkRequestException
     */
    public function getAggregateArea(
        ObjectIdentifierCollection $objectIdentifiers,
        GeometryFormat $geometryFormat = null
    ): UldkObject {
        $options = new ResponseContentOptions();

        if ($geometryFormat !== null) {
            $options->setGeometryFormat($geometryFormat);
        }

        $query = new Query([
            RequestName::GET_AGGREGATE_AREA,
            $objectIdentifiers,
            $options,
        ]);
        $result = $this->makeRequest($query);

        return $this->normalizer->denormalize($result[0], $query);
    }

    /**
     * @throws UldkRequestException
     */
    private function getObjectById(
        RequestName $requestName,
        ObjectIdentifier $objectId,
        ?ResponseContentOptions $options
    ): UldkObject {
        $query = new Query([
            $requestName,
            $objectId,
            $options ?? $this->defaultOptions,
        ]);
        $result = $this->makeRequest($query);

        return $this->normalizer->denormalize($result[0], $query);
    }

    /**
     * @throws UldkRequestException
     */
    private function getObjectByCoordinates(
        RequestName $requestName,
        ObjectCoordinates $coordinates,
        ResponseContentOptions $options
    ): UldkObject {
        $query = new Query([
            $requestName,
            $coordinates,
            $options ?? $this->defaultOptions,
        ]);
        $result = $this->makeRequest($query);

        return $this->normalizer->denormalize($result[0], $query);
    }

    /**
     * @throws UldkRequestException
     */
    private function makeRequest(Query $query): array
    {
        return $this->httpRequest->execute($query);
    }
}
