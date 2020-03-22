<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

class ObjectCoordinates implements QueryElement
{
    public const ELEMENT_KEY = 'xy';

    private float $x;

    private float $y;

    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s,%s',
            $this->x,
            $this->y,
        );
    }

    public function getElementKey(): string
    {
        return self::ELEMENT_KEY;
    }
}
