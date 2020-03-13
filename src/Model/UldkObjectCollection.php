<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Model;

class UldkObjectCollection implements \IteratorAggregate
{
    /** @var UldkObject[] */
    private array $uldkObjects = [];

    public function add(UldkObject $uldkObject): void
    {
        $this->uldkObjects[] = $uldkObject;
    }

    public function getFirst(): UldkObject
    {
        return $this->uldkObjects[0];
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->uldkObjects);
    }
}
