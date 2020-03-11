<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Domain;

class ObjectIdentifierCollection implements \IteratorAggregate
{
    /** @var ObjectIdentifier[] */
    private array $identifiers = [];

    public function __construct(array $objectIdentifiers)
    {
        foreach ($objectIdentifiers as $objectIdentifier) {
            $this->add($objectIdentifier);
        }
    }

    public function add(ObjectIdentifier $objectIdentifier): void
    {
        $this->identifiers[] = $objectIdentifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->identifiers);
    }
}
