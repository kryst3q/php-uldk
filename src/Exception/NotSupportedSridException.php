<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Exception;

use Kryst3q\PhpUldk\Domain\CoordinateSystem;

class NotSupportedSridException extends \InvalidArgumentException
{
    public function __construct(string $invalidValue)
    {
        parent::__construct(sprintf(
            'Spatial reference identifier "%s" is not supported. Supported identifiers: "%s".',
            $invalidValue,
            implode('", "', CoordinateSystem::getSupportedSrids())
        ));
    }
}
