<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Exception;

use Kryst3q\PhpUldk\Domain\RequestName;

class InvalidRequestValueException extends \DomainException
{
    public function __construct(string $invalidValue)
    {
        parent::__construct(sprintf(
            '"%s" is invalid request value. Valid values: "%s".',
            $invalidValue,
            implode('", "', RequestName::getValidValues())
        ));
    }
}
