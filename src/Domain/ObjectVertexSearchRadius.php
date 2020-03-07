<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

class ObjectVertexSearchRadius
{
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
        return (string)$this->radius;
    }
}
