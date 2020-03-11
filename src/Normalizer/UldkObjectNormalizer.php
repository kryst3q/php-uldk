<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Normalizer;

use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\Factory\BoundingBoxFactory;
use Kryst3q\PhpUldk\Factory\GeometryFactory;
use Kryst3q\PhpUldk\Model\UldkObject;

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
        $options = $query->getElement(ResponseContentOptions::ELEMENT_KEY);
        $objectData = explode('|', $result);
        $uldkObject = new UldkObject($this->geometryFactory->createFromObjectData($objectData, $query));

        $index = 0;
        foreach ($options->getOptions() as $option) {
            switch ($option) {
                case ResponseContentOptions::OPT_OBJECT_ID:
                    $uldkObject->setIdentifier($objectData[$index]);
                    break;
                case ResponseContentOptions::OPT_BBOX:
                    $uldkObject->setBoundingBox($this->boundingBoxFactory->createFromString($objectData[$index]));
                    break;
                case ResponseContentOptions::OPT_VOIVODESHIP_NAME:
                    $uldkObject->setVoivodeshipName($objectData[$index]);
                    break;
                case ResponseContentOptions::OPT_COUNTY_NAME:
                    $uldkObject->setCountyName($objectData[$index]);
                    break;
                case ResponseContentOptions::OPT_COMMUNE_NAME:
                    $uldkObject->setCommuneName($objectData[$index]);
                    break;
                case ResponseContentOptions::OPT_REGION_NAME_OR_NR:
                    $uldkObject->setRegionName($objectData[$index]);
                    break;
                case ResponseContentOptions::OPT_PARCEL_NR:
                    $uldkObject->setParcelNameOrNumber($objectData[$index]);
                    break;
            }

            ++$index;
        }

        return $uldkObject;
    }
}
