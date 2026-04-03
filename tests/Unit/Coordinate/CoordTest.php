<?php

declare(strict_types=1);

namespace App\Tests\Unit\Coordinate;

use App\Bfs\Coordinate\Coord;
use PHPUnit\Framework\TestCase;

class CoordTest extends TestCase
{
    public function testCoord(): void
    {
        $coord = new Coord(53.5, 10.5);

        $this->assertEquals(53.5, $coord->getLatitude());
        $this->assertEquals(10.5, $coord->getLongitude());
    }

    public function testNegativeCoordinates(): void
    {
        $coord = new Coord(-33.8688, -151.2093);

        $this->assertEquals(-33.8688, $coord->getLatitude());
        $this->assertEquals(-151.2093, $coord->getLongitude());
    }

    public function testZeroCoordinates(): void
    {
        $coord = new Coord(0.0, 0.0);

        $this->assertEquals(0.0, $coord->getLatitude());
        $this->assertEquals(0.0, $coord->getLongitude());
    }

    public function testExtremeValues(): void
    {
        $coord = new Coord(90.0, 180.0);

        $this->assertEquals(90.0, $coord->getLatitude());
        $this->assertEquals(180.0, $coord->getLongitude());
    }
}
