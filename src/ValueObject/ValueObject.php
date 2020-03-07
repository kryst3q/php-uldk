<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\ValueObject;

abstract class ValueObject
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->validate($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    abstract protected function validate(string $value): void;
}
