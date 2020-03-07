<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

class Query
{
    private RequestName $request;
    private ?ResponseContentOptions $result;
    private ?ObjectIdentifier $id;
    private ?ObjectCoordinates $xy;
    private ?ObjectVertexSearchRadius $radius;

    public function __construct(
        RequestName $request,
        ?ObjectIdentifier $id = null,
        ?ResponseContentOptions $result = null,
        ?ObjectCoordinates $xy = null,
        ?ObjectVertexSearchRadius $radius = null
    ) {
        $this->request = $request;
        $this->result = $result;
        $this->id = $id;
        $this->xy = $xy;
        $this->radius = $radius;
    }

    public function __toString(): string
    {
        return '?'.http_build_query($this->getQueryElements(), '', '&');
    }

    public function getResponseContentOptions(): ?ResponseContentOptions
    {
        return $this->result;
    }

    private function getQueryElements(): array
    {
        $queryElements = [
            'request' => (string)$this->request
        ];

        if ($this->id !== null) {
            $queryElements['id'] = (string)$this->id;
        }

        $result = (string)$this->result;
        if ($result !== '') {
            $queryElements['result'] = $result;
        }

        if ($this->xy !== null) {
            $queryElements['xy'] = (string)$this->xy;
        }

        if ($this->radius !== null) {
            $queryElements['radius'] = (string)$this->radius;
        }

        return $queryElements;
    }
}
