<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

use Kryst3q\PhpUldk\ValueObject\CoordinateSystem;

class ObjectCoordinates
{
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
            $this->srid === null ? '' : ','.$this->srid
        );
    }
}
