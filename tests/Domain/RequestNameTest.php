<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests\Domain;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\Domain\RequestName;
use Kryst3q\PhpUldk\Exception\InvalidRequestValueException;

class RequestNameTest extends Unit
{
    /**
     * @dataProvider constructSuccessfulIfValueIsValidDataProvider
     */
    public function testConstructSuccessfulIfValueIsValid(string $value): void
    {
        $request = new RequestName($value);

        self::assertSame($value, $request->getValue());
        self::assertSame($value, (string)$request);
    }

    /**
     * @dataProvider throwExceptionIfValueIsInvalidDataProvider
     */
    public function testThrowExceptionIfValueIsInvalid(string $value): void
    {
        $this->expectException(InvalidRequestValueException::class);

        new RequestName($value);
    }

    public function constructSuccessfulIfValueIsValidDataProvider(): array
    {
        $data = [];
        $data[] = array_map(
            function ($value) {
                return $value;
            },
            RequestName::getValidValues()
        );

        return $data;
    }

    public function throwExceptionIfValueIsInvalidDataProvider(): array
    {
        return [
            [''],
            ['GetParceById'],
            ['1']
        ];
    }
}
