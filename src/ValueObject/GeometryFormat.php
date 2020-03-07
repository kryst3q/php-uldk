<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\ValueObject;

use Kryst3q\PhpUldk\Exception\NotSupportedGeometryFormatException;

class GeometryFormat extends ValueObject
{
    public const FORMAT_WKT = 'geom_wkt';
    public const FORMAT_WKB = 'geom_wkb';

    public const FORMAT_DEFAULT = self::FORMAT_WKB;

    public static function getValidGeometryFormatValues(): array
    {
        return [
            GeometryFormat::FORMAT_WKB,
            GeometryFormat::FORMAT_WKT
        ];
    }

    protected function validate(string $geometryFormat): void
    {
        if (!in_array($geometryFormat, GeometryFormat::getValidGeometryFormatValues())) {
            throw new NotSupportedGeometryFormatException($geometryFormat);
        }
    }
}
