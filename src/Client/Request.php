<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Client;

use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Exception\UldkRequestException;
use Kryst3q\PhpUldk\Factory\ResponseFactory;

class Request implements HttpRequest
{
    private string $url;

    private ResponseFactory $responseFactory;

    public function __construct(string $url, ResponseFactory $responseFactory)
    {
        $this->url = $url;
        $this->responseFactory = $responseFactory;
    }

    public function execute(Query $query): HttpResponse
    {
        $handle = curl_init();
        curl_setopt($handle, \CURLOPT_URL, $this->url . $query);
        curl_setopt($handle, \CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($handle);
        curl_close($handle);

        if ($result === false) {
            throw new UldkRequestException(curl_error($handle));
        }

        return $this->responseFactory->create($result, $query);
    }
}
