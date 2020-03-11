<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

interface QueryElement
{
    public function getElementKey(): string;
    public function __toString(): string;
}
