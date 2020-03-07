<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Client;

use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Exception\UldkRequestException;

class CurlRequest implements HttpRequest
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function execute(Query $query): string
    {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $this->url . $query);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($handle);
        curl_close($handle);

        if ($result === false) {
            throw new UldkRequestException(curl_error($handle));
        }

        return $result;
    }
}
