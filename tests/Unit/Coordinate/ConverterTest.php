<?php

declare(strict_types=1);

namespace App\Tests\Unit\Coordinate;

use App\Bfs\Coordinate\Converter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    #[DataProvider('coordinateProvider')]
    public function testCoordinateConversion(string $coordinateString, float $expectedLatitude, float $expectedLongitude): void
    {
        $coord = Converter::convert($coordinateString);

        $this->assertEqualsWithDelta($expectedLatitude, $coord->getLatitude(), 0.0000001);
        $this->assertEqualsWithDelta($expectedLongitude, $coord->getLongitude(), 0.0000001);
    }

    public static function coordinateProvider(): array
    {
        return [
            ["50°25'26\"  Nord<br>\n7°25'16\"  Ost", 50.4238888889, 7.4211111111],
            ["53°14'49\" Nord<br>\n10°27'24\" Ost", 53.2469444444, 10.4566666667],
            ["51°32' Nord<br>\n12°56' Ost", 51.5333333333, 12.9333333333],
        ];
    }

    public function testInvalidInputThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Converter::convert('invalid coordinate string');
    }

    public function testEmptyInputThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Converter::convert('');
    }
}
