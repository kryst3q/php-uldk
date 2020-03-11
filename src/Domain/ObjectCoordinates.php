<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

class ObjectCoordinates implements QueryElement
{
    public const ELEMENT_KEY = 'xy';

    private float $x;

    private float $y;

    private ?CoordinateSystem $srid;

    public function __construct(float $x, float $y, CoordinateSystem $srid = null)
    {
        $this->x = $x;
        $this->y = $y;
        $this->srid = $srid;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s,%s%s',
            $this->x,
            $this->y,
            $this->srid === null ? '' : ',' . $this->srid
        );
    }

    public function getElementKey(): string
    {
        return self::ELEMENT_KEY;
    }
}
