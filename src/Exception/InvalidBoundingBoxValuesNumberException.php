<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Exception;

use Kryst3q\PhpUldk\Factory\BoundingBoxFactory;

class InvalidBoundingBoxValuesNumberException extends \InvalidArgumentException
{
    public function __construct(int $actualValuesNumber)
    {
        parent::__construct(sprintf(
            'Invalid number of bbox values. %s present and %s expected.',
            $actualValuesNumber,
            BoundingBoxFactory::BBOX_VALUES_NUMBER
        ));
    }
}
