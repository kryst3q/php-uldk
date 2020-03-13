<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Client;

use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Exception\UldkRequestException;

interface HttpRequest
{
    /**
     * @throws UldkRequestException
     */
    public function execute(Query $query): array;
}
