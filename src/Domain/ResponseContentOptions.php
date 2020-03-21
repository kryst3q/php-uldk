<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

use Kryst3q\PhpUldk\ValueObject\GeometryFormat;

class ResponseContentOptions implements QueryElement
{
    public const ELEMENT_KEY = 'result';

    public const OPT_OBJECT_ID = 'teryt';

    public const OPT_VOIVODESHIP_NAME = 'voivodeship';

    public const OPT_COUNTY_NAME = 'county';

    public const OPT_COMMUNE_NAME = 'commune';

    public const OPT_REGION_NAME_OR_NR = 'region';

    public const OPT_PARCEL_NR = 'parcel';

    public const OPT_BBOX = 'geom_extent';

    public const OPT_GEOMETRY_FORMAT = 'geom_format';

    /** @var string[] */
    private array $options = [];

    private ?CoordinateSystem $coordinateSystem = null;

    public function __toString(): string
    {
        return implode(',', $this->options);
    }

    public function requestBoundaryBox(): self
    {
        $this->options[] = self::OPT_BBOX;

        return $this;
    }

    public function setGeometryFormat(GeometryFormat $format): self
    {
        $this->options[self::OPT_GEOMETRY_FORMAT] = $format;

        return $this;
    }

    public function setCoordinateSystem(CoordinateSystem $coordinateSystem): self
    {
        $this->coordinateSystem = $coordinateSystem;

        return $this;
    }

    public function requestObjectIdentifier(): self
    {
        $this->options[] = self::OPT_OBJECT_ID;

        return $this;
    }

    public function requestVoivodeshipName(): self
    {
        $this->options[] = self::OPT_VOIVODESHIP_NAME;

        return $this;
    }

    public function requestCountyName(): self
    {
        $this->options[] = self::OPT_COUNTY_NAME;

        return $this;
    }

    public function requestCommuneName(): self
    {
        $this->options[] = self::OPT_COMMUNE_NAME;

        return $this;
    }

    public function requestRegionNameOrNumber(): self
    {
        $this->options[] = self::OPT_REGION_NAME_OR_NR;

        return $this;
    }

    public function requestParcelNumber(): self
    {
        $this->options[] = self::OPT_PARCEL_NR;

        return $this;
    }

    public function getOptions(): array
    {
        return array_values($this->options);
    }

    public function getRequestedGeometryFormat(): GeometryFormat
    {
        return $this->options[self::OPT_GEOMETRY_FORMAT] ?? new GeometryFormat(GeometryFormat::FORMAT_DEFAULT);
    }

    public function getRequestedCoordinateSystem(): CoordinateSystem
    {
        return $this->coordinateSystem ?? new CoordinateSystem(CoordinateSystem::DEFAULT);
    }

    public function getElementKey(): string
    {
        return self::ELEMENT_KEY;
    }
}
