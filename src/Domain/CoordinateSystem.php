<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

use Kryst3q\PhpUldk\Exception\NotSupportedSridException;
use Kryst3q\PhpUldk\ValueObject\ValueObject;

class CoordinateSystem extends ValueObject implements QueryElement
{
    public const ELEMENT_KEY = 'srid';

    /**
     * ETRS89 / Poland CS92
     * @see https://epsg.io/2180
     */
    public const SRID_2180 = '2180';

    /**
     * WGS 84 -- WGS84 - World Geodetic System 1984, used in GPS
     * @see https://epsg.io/4326
     */
    public const SRID_4326 = '4326';

    public const DEFAULT = self::SRID_2180;

    public static function getSupportedSrids(): array
    {
        return [
            self::SRID_2180,
            self::SRID_4326,
        ];
    }

    protected function validate(string $value): void
    {
        if (!in_array($value, self::getSupportedSrids())) {
            throw new NotSupportedSridException($value);
        }
    }

    public function getElementKey(): string
    {
        return self::ELEMENT_KEY;
    }
}
