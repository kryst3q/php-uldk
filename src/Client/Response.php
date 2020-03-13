<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Client;

use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Model\UldkObjectCollection;

class Response implements HttpResponse
{
    private UldkObjectCollection $objects;

    private Query $query;

    public function __construct(UldkObjectCollection $objects, Query $query)
    {
        $this->objects = $objects;
        $this->query = $query;
    }

    public function getObjects(): UldkObjectCollection
    {
        return $this->objects;
    }

    public function getQuery(): Query
    {
        return $this->query;
    }
}
