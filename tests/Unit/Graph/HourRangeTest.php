<?php declare(strict_types=1);

namespace App\Tests\Unit\Graph;

use App\Bfs\Graph\HourRange\HourRange;
use Imagine\Gd\Imagine;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HourRangeTest extends TestCase
{
    #[DataProvider('graphFilenameProvider')]
    public function testHourRange(string $graphFilename, int $expectedStartHour, int $expectedEndHour): void
    {
        $imagine = new Imagine();
        $image = $imagine->open($graphFilename);

        $hourRange = HourRange::calculate($image);

        $this->assertEquals($expectedStartHour, $hourRange->getStartHour());
        $this->assertEquals($expectedEndHour, $hourRange->getEndHour());
    }

    public static function graphFilenameProvider(): array
    {
        return [
            [__DIR__ . '/../../graph/hamburg1.png', 6, 21],
            [__DIR__ . '/../../graph/lueneburg5.png', 7, 18],
        ];
    }
}
