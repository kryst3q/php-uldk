<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\ValueObject;

use Kryst3q\PhpUldk\Exception\NotSupportedGeometryTypeException;

class GeometryType extends ValueObject
{
    public const TYPE_MULTI_LINE_STRING = 'MULTILINESTRING';
    public const TYPE_GEOMETRY_COLLECTION = 'GEOMETRYCOLLECTION';
    public const TYPE_POINT = 'POINT';
    public const TYPE_MULTI_POINT = 'MULTIPOINT';
    public const TYPE_MULTI_POLYGON = 'MULTIPOLYGON';
    public const TYPE_LINE_STRING = 'LINESTRING';
    public const TYPE_POLYGON = 'POLYGON';

    public static function getSupportedGeometryTypes(): array
    {
        return [
            self::TYPE_GEOMETRY_COLLECTION,
            self::TYPE_MULTI_POINT,
            self::TYPE_LINE_STRING,
            self::TYPE_MULTI_LINE_STRING,
            self::TYPE_POINT,
            self::TYPE_MULTI_POLYGON,
            self::TYPE_POLYGON
        ];
    }

    protected function validate(string $value): void
    {
        if (!in_array($value, self::getSupportedGeometryTypes())) {
            throw new NotSupportedGeometryTypeException($value);
        }
    }
}
