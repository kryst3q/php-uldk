<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Exception;

use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;

class NotSupportedGeometryFormatException extends \DomainException
{
    public function __construct(string $invalidValue)
    {
        parent::__construct(sprintf(
            'Geometry format "%s" is not supported. Supported formats: "%s".',
            $invalidValue,
            implode('", "', GeometryFormat::getValidGeometryFormatValues())
        ));
    }
}
