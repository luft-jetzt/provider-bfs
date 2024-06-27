<?php declare(strict_types=1);

namespace App\Tests\Unit\Graph;

use App\Bfs\Graph\Point;
use Imagine\Gd\Imagine;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    #[DataProvider('graphFilenameProvider')]
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
            [__DIR__ . '/../../graph/hamburg1.png', 472, 329],
            [__DIR__ . '/../../graph/lueneburg1.png', 483, 426],
            [__DIR__ . '/../../graph/lueneburg2.png', 350, 234],
            [__DIR__ . '/../../graph/lueneburg3.png', 383, 261],
        ];
    }
}
