<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Tests;

use Codeception\Test\Unit;
use Kryst3q\PhpUldk\PhpUldk;

class PhpUldkTest extends Unit
{
    private string $parcelId = '141201_1.0001.6509';
    private PhpUldk $phpUldk;

    protected function _before()
    {

        $this->phpUldk = new PhpUldk();
    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
        $result = $this->phpUldk->getParcelById($this->parcelId);
        self::assertInstanceOf(UldkObject::class, $result);
    }
}
