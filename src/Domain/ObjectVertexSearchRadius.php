<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

class ObjectVertexSearchRadius implements QueryElement
{
    public const ELEMENT_KEY = 'radius';

    private int $radius;

    public function __construct(int $radius)
    {
        $this->radius = $radius;
    }

    public function getValue(): int
    {
        return $this->radius;
    }

    public function __toString(): string
    {
        return (string) $this->radius;
    }

    public function getElementKey(): string
    {
        return self::ELEMENT_KEY;
    }
}
