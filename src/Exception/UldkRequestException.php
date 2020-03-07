<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Exception;

class UldkRequestException extends \Exception
{
    public function __construct(string $error)
    {
        parent::__construct(\sprintf(
            'An error occurred during execution of ULDK request: %s.',
            $error
        ));
    }
}
