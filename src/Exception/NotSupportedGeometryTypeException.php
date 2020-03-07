<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Exception;

use Kryst3q\PhpUldk\ValueObject\GeometryType;

class NotSupportedGeometryTypeException extends \DomainException
{
    public function __construct(string $invalidValue)
    {
        parent::__construct(sprintf(
            'Geometry type "%s" is not supported. Supported types: "%s".',
            $invalidValue,
            implode('", "', GeometryType::getSupportedGeometryTypes())
        ));
    }
}
