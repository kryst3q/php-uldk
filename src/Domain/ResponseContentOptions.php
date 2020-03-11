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

    /**
     * @var string[]
     */
    private array $options = [];

    public function __toString(): string
    {
        return implode(',', $this->options);
    }

    public function addBoundaryBox(): self
    {
        $this->options[] = self::OPT_BBOX;

        return $this;
    }

    public function setGeometryFormat(GeometryFormat $format): self
    {
        $this->options[self::OPT_GEOMETRY_FORMAT] = $format;

        return $this;
    }

    public function addObjectIdentifier(): self
    {
        $this->options[] = self::OPT_OBJECT_ID;

        return $this;
    }

    public function addVoivodeshipName(): self
    {
        $this->options[] = self::OPT_VOIVODESHIP_NAME;

        return $this;
    }

    public function addCountyName(): self
    {
        $this->options[] = self::OPT_COUNTY_NAME;

        return $this;
    }

    public function addCommuneName(): self
    {
        $this->options[] = self::OPT_COMMUNE_NAME;

        return $this;
    }

    public function addRegionNameOrNumber(): self
    {
        $this->options[] = self::OPT_REGION_NAME_OR_NR;

        return $this;
    }

    public function addParcelNumber(): self
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

    public function getElementKey(): string
    {
        return self::ELEMENT_KEY;
    }
}
