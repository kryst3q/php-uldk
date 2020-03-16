<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

class Query
{
    /** @var QueryElement[] */
    private array $elements = [];

    public function __construct(array $elements)
    {
        foreach ($elements as $element) {
            if ($element === null) {
                continue;
            }
            
            $this->addElement($element);
        }
    }

    public function __toString(): string
    {
        return '?' . http_build_query($this->prepareElements(), '', '&');
    }

    public function addElement(QueryElement $element): void
    {
        $this->elements[$element->getElementKey()] = $element;
    }

    public function getElement(string $key): QueryElement
    {
        return $this->elements[$key];
    }
    
    public function hasElement(string $key): bool
    {
        return isset($this->elements[$key]);
    }

    private function prepareElements(): array
    {
        $queryElements = [];

        foreach ($this->elements as $element) {
            $stringifiedElement = (string) $element;

            if ($stringifiedElement !== '') {
                $queryElements[$element->getElementKey()] = $stringifiedElement;
            }
        }

        return $queryElements;
    }
}
