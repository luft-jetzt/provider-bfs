<?php

namespace App\Tests\Unit\Graph;

use App\Bfs\Graph\Point;
use App\Bfs\Graph\StepSize;
use Imagine\Gd\Imagine;
use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{


    /**
     * @dataProvider graphFilenameProvider
     */
    public function testPoint(string $graphFilename, int $expectedPointX, int $expectedPointY): void
    {
        $imagine = new Imagine();
        $image = $imagine->open($graphFilename);

        $point = Point::detectCurrentPoint($image);

        $this->assertEquals($expectedPointX, $point->getX());
        $this->assertEquals($expectedPointY, $point->getY());
    }

    public static function graphFilenameProvider(): array
    {
        return [
            [__DIR__ . '/../../graph/hamburg1.png', 21, 55],
            [__DIR__ . '/../../graph/lueneburg1.png', 32, 55],
            [__DIR__ . '/../../graph/lueneburg2.png', 21, 55],
            [__DIR__ . '/../../graph/lueneburg3.png', 21, 55],
        ];
    }
}
