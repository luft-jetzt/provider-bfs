<?php

namespace App\Tests;

use App\Bfs\Coordinate\Converter;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    public function testSomething(): void
    {
        $coordinateString = "53°14'49\" Nord<br>\n10°27'24\" Ost";

        $decimalCoordinate = Converter::convert($coordinateString);

        $this->assertEquals(53.2469444444, $decimalCoordinate['lat']);
        $this->assertEquals(10.4566666667, $decimalCoordinate['lng']);
    }
}
