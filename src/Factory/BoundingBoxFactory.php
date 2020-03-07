<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Factory;

use Kryst3q\PhpUldk\Exception\InvalidBoundingBoxValuesNumberException;
use Kryst3q\PhpUldk\Exception\InvalidBoundingBoxValuesTypeException;
use Kryst3q\PhpUldk\Model\BoundingBox;

class BoundingBoxFactory
{
    public const BBOX_VALUES_NUMBER = 4;

    public function createFromString(string $bboxString): BoundingBox
    {
        $bboxArray = explode(',', $bboxString);

        $this->validate($bboxArray);

        return new BoundingBox(
            (float)$bboxArray[0],
            (float)$bboxArray[1],
            (float)$bboxArray[2],
            (float)$bboxArray[3]
        );
    }

    private function validate(array $bboxArray): void
    {
        $actualValuesNumber = count($bboxArray);
        if ($actualValuesNumber !== self::BBOX_VALUES_NUMBER) {
            throw new InvalidBoundingBoxValuesNumberException($actualValuesNumber);
        }

        foreach ($bboxArray as $bboxValue) {
            $floatVal = floatval($bboxValue);
            if (!$floatVal || intval($floatVal) == $floatVal) {
                throw new InvalidBoundingBoxValuesTypeException();
            }
        }
    }
}
