<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Client;

use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Model\UldkObjectCollection;

interface HttpResponse
{
    public function getObjects(): UldkObjectCollection;

    public function getQuery(): Query;
}
