<?php

declare(strict_types=1);

namespace Model;

use Kryst3q\PhpUldk\Model\UldkObject;

class UldkObjectCollection implements \IteratorAggregate
{
    private array $uldkObjects = [];

    public function add(UldkObject $uldkObject): void
    {
        $this->uldkObjects[] = $uldkObject;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->uldkObjects);
    }
}