<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Normalizer;

use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\Factory\BoundingBoxFactory;
use Kryst3q\PhpUldk\Factory\GeometryFactory;
use Kryst3q\PhpUldk\Model\Geometry;
use Kryst3q\PhpUldk\Model\UldkObject;
use Kryst3q\PhpUldk\ValueObject\CoordinateSystem;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;
use Kryst3q\PhpUldk\ValueObject\GeometryType;

class UldkObjectNormalizer
{
    private GeometryFactory $geometryFactory;
    private BoundingBoxFactory $boundingBoxFactory;

    public function __construct(GeometryFactory $geometryFactory, BoundingBoxFactory $boundingBoxFactory)
    {
        $this->geometryFactory = $geometryFactory;
        $this->boundingBoxFactory = $boundingBoxFactory;
    }

    public function denormalize(string $result, Query $query): UldkObject
    {
        $results = explode("\n", $result);
        $noOfResults = (int)array_shift($results);

        $options = $query->getResponseContentOptions();

        if ($options !== null && $options->getRequestedGeometryFormat()->getValue() === GeometryFormat::FORMAT_WKT) {
            foreach ($results as $rawObjectData) {
                $objectData = explode('|', $rawObjectData);
                $geometryString = $objectData[count($objectData) - 1];
            }

            $resultArray = explode(';', substr($result, 1));
            $metadata = explode('|', $resultArray[0]);


            $sridIndex = count($metadata) - 1;
            $srid = explode('=', $metadata[$sridIndex])[1];

            $geometryString = $resultArray[1];

            $geometryType = strstr($geometryString, '(', true);
            $geometryFormat = GeometryFormat::FORMAT_WKT;
        } else {
            /*
             * TODO: Read srid from decoded response geometry instead of getting it from query.
             */
            $srid = $options->getRequestedCoordinateSystem();
            $metadata = explode('|', substr($result, 1));
            $geometryString = $metadata[count($metadata) - 1];
            $geometryFormat = GeometryFormat::FORMAT_WKB;
            /*
             * TODO: Find how decode response geometry and get type from there.
             */
            $geometryType = 'POLYGON';
        }

        $geometry = new Geometry(
            new CoordinateSystem($srid),
            $geometryString,
            new GeometryType($geometryType),
            new GeometryFormat($geometryFormat)
        );

        $uldkObject = new UldkObject($geometry);

        $index = 0;
        foreach ($query->getResponseContentOptions()->getOptions() as $option) {
            switch ($option) {
                case ResponseContentOptions::OPT_OBJECT_ID:
                    $uldkObject->setIdentifier($metadata[$index]);
                    break;
                case ResponseContentOptions::OPT_BBOX:
                    $uldkObject->setBoundingBox($this->boundingBoxFactory->createFromString($metadata[$index]));
                    break;
                case ResponseContentOptions::OPT_VOIVODESHIP_NAME:
                    $uldkObject->setVoivodeshipName($metadata[$index]);
                    break;
                case ResponseContentOptions::OPT_COUNTY_NAME:
                    $uldkObject->setCountyName($metadata[$index]);
                    break;
                case ResponseContentOptions::OPT_COMMUNE_NAME:
                    $uldkObject->setCommuneName($metadata[$index]);
                    break;
                case ResponseContentOptions::OPT_REGION_NAME_OR_NR:
                    $uldkObject->setRegionName($metadata[$index]);
                    break;
                case ResponseContentOptions::OPT_PARCEL_NR:
                    $uldkObject->setParcelNameOrNumber($metadata[$index]);
                    break;
            }

            ++$index;
        }

        return $uldkObject;
    }
}
