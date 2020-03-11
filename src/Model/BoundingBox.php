<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Model;

class BoundingBox
{
    private float $minLongitude;

    private float $minLatitude;

    private float $maxLongitude;

    private float $maxLatitude;

    public function __construct(float $minLongitude, float $minLatitude, float $maxLongitude, float $maxLatitude)
    {
        $this->minLongitude = $minLongitude;
        $this->minLatitude = $minLatitude;
        $this->maxLongitude = $maxLongitude;
        $this->maxLatitude = $maxLatitude;
    }

    public function getValue(): string
    {
        return \sprintf(
            '%s,%s,%s,%s',
            $this->minLongitude,
            $this->minLatitude,
            $this->maxLongitude,
            $this->maxLatitude
        );
    }
}
