<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

use Kryst3q\PhpUldk\ValueObject\ValueObject;

class ObjectIdentifier extends ValueObject implements QueryElement
{
    public const ELEMENT_KEY = 'id';

    protected function validate(string $value): void
    {
    }

    public function getElementKey(): string
    {
        return self::ELEMENT_KEY;
    }
}
