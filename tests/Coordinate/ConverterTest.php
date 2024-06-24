<?php declare(strict_types=1);

namespace App\Tests\Coordinate;

use App\Bfs\Coordinate\Converter;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    /**
     * @dataProvider coordinateProvider
     */
    public function testCoordinateConversion(string $coordinateString, float $expectedLatitude, float $expectedLongitude): void
    {
        $coord = Converter::convert($coordinateString);

        $this->assertEquals($expectedLatitude, $coord->getLatitude());
        $this->assertEquals($expectedLongitude, $coord->getLongitude());
    }

    public function coordinateProvider(): array
    {
        return [
            ["50°25'26\"  Nord<br>\n7°25'16\"  Ost", 50.4238888889, 7.4211111111],
            ["53°14'49\" Nord<br>\n10°27'24\" Ost", 53.2469444444, 10.4566666667],
        ];
    }
}
