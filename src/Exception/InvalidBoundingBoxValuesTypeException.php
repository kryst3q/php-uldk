<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Exception;

class InvalidBoundingBoxValuesTypeException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('At least one of bbox values is not float.');
    }
}
