<?php declare(strict_types=1);

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
}
