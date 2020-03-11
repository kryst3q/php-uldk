<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Model;

use Kryst3q\PhpUldk\Domain\CoordinateSystem;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;
use Kryst3q\PhpUldk\ValueObject\GeometryType;

class Geometry
{
    protected CoordinateSystem $coordinateSystem;
    protected GeometryFormat $format;
    protected string $geometry;
    protected GeometryType $type;

    public function __construct(
        CoordinateSystem $coordinateSystem,
        string $geometry,
        GeometryType $type,
        GeometryFormat $format
    ) {
        $this->coordinateSystem = $coordinateSystem;
        $this->geometry = $geometry;
        $this->type = $type;
        $this->format = $format;
    }

    public function getCoordinateSystem(): CoordinateSystem
    {
        return $this->coordinateSystem;
    }

    public function getFormat(): GeometryFormat
    {
        return $this->format;
    }

    public function getGeometry(): string
    {
        return $this->geometry;
    }

    public function getType(): GeometryType
    {
        return $this->type;
    }
}
