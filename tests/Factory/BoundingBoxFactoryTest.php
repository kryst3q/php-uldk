<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests\Factory;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Exception\InvalidBoundingBoxValuesNumberException;
use Kryst3q\PhpUldk\Exception\InvalidBoundingBoxValuesTypeException;
use Kryst3q\PhpUldk\Factory\BoundingBoxFactory;
use Kryst3q\PhpUldk\Model\BoundingBox;

class BoundingBoxFactoryTest extends Unit
{
    private BoundingBoxFactory $factory;

    protected function _before()
    {
        parent::_before();

        $this->factory = new BoundingBoxFactory();
    }

    public function testSuccessfullyCreateFromString(): void
    {
        $bboxString = '21.5893322010412,52.1716703344228,21.5904269793163,52.1722747035024';
        $bbox = $this->factory->createFromString($bboxString);

        self::assertInstanceOf(BoundingBox::class, $bbox);
    }

    public function testThrowExceptionIfBboxValuesNumberIsInvalid(): void
    {
        $this->expectException(InvalidBoundingBoxValuesNumberException::class);

        $bboxString = '21.5893322010412,52.1716703344228,21.5904269793163';
        $this->factory->createFromString($bboxString);
    }

    public function testThrowExceptionIfAnyOfBboxValuesIsNotFloat(): void
    {
        $this->expectException(InvalidBoundingBoxValuesTypeException::class);

        $bboxString = '21.5893322010412,52.1716703344228,21590,52.1722747035024';
        $this->factory->createFromString($bboxString);
    }
}
