<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Factory;

use Kryst3q\PhpUldk\Domain\CoordinateSystem;
use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\Model\Geometry;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;
use Kryst3q\PhpUldk\ValueObject\GeometryType;

class GeometryFactory
{
    public function createFromObjectData(array $objectData, Query $query): Geometry
    {
        $options = $query->getElement(ResponseContentOptions::ELEMENT_KEY);
        $geometryString = array_pop($objectData);
        /*
         * TODO: Read srid from decoded response geometry instead of getting it from query.
         */
        $srid = $query->getElement(CoordinateSystem::ELEMENT_KEY) ?? CoordinateSystem::DEFAULT;

        if ($options !== null && $options->getRequestedGeometryFormat()->getValue() === GeometryFormat::FORMAT_WKT) {
            $geometryType = strstr(explode(';', $geometryString)[1], '(', true);
            $geometryFormat = GeometryFormat::FORMAT_WKT;
        } else {
            $geometryFormat = GeometryFormat::FORMAT_WKB;
            /*
             * TODO: Find how decode response geometry and get type from there.
             */
            $geometryType = 'POLYGON';
        }

        return new Geometry(
            new CoordinateSystem($srid),
            $geometryString,
            new GeometryType($geometryType),
            new GeometryFormat($geometryFormat)
        );
    }
}
