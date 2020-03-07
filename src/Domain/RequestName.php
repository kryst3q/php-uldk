<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

use Kryst3q\PhpUldk\Exception\InvalidRequestValueException;
use Kryst3q\PhpUldk\ValueObject\ValueObject;

class RequestName extends ValueObject
{
    public const GET_PARCEL_BY_ID = 'GetParcelById';
    public const GET_PARCEL_BY_ID_OR_NR = 'GetParcelByIdOrNr';
    public const GET_PARCEL_BY_COORDINATES = 'GetParcelByXY';

    public const GET_REGION_BY_ID = 'GetRegionById';
    public const GET_REGION_BY_COORDINATES = 'GetRegionByXY';

    public const GET_COMMUNE_BY_ID = 'GetCommuneById';
    public const GET_COMMUNE_BY_COORDINATES = 'GetCommuneByXY';

    public const GET_COUNTY_BY_ID = 'GetCountyById';
    public const GET_COUNTY_BY_COORDINATES = 'GetCountyByXY';

    public const GET_VOIVODESHIP_BY_ID = 'GetVoivodeshipById';
    public const GET_VOIVODESHIP_BY_COORDINATES = 'GetVoivodeshipByXY';

    public const GET_AGGREGATE_AREA = 'GetAggregateArea';

    public const SNAP_TO_POINT = 'SnapToPoint';

    public static function getValidValues(): array
    {
        return [
            self::GET_PARCEL_BY_ID,
            self::GET_PARCEL_BY_ID_OR_NR,
            self::GET_PARCEL_BY_COORDINATES,
            self::GET_REGION_BY_ID,
            self::GET_REGION_BY_COORDINATES,
            self::GET_COMMUNE_BY_ID,
            self::GET_COMMUNE_BY_COORDINATES,
            self::GET_COUNTY_BY_ID,
            self::GET_COUNTY_BY_COORDINATES,
            self::GET_VOIVODESHIP_BY_ID,
            self::GET_VOIVODESHIP_BY_COORDINATES,
            self::GET_AGGREGATE_AREA,
            self::SNAP_TO_POINT
        ];
    }

    protected function validate(string $value): void
    {
        if (!in_array($value, self::getValidValues())) {
            throw new InvalidRequestValueException((string)$value);
        }
    }
}
