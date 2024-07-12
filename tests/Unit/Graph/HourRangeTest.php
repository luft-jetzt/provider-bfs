<?php declare(strict_types=1);

namespace App\Tests\Unit\Graph;

use App\Bfs\Cache\HourRangeCacheInterface;
use App\Bfs\Graph\HourRange\HourRange;
use App\Bfs\Graph\HourRange\HourRangeModel;
use Imagine\Gd\Imagine;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HourRangeTest extends TestCase
{
    #[DataProvider('graphFilenameProvider')]
    public function testHourRange(string $graphFilename, int $expectedStartHour, int $expectedEndHour): void
    {
        $expectedHourRange = new HourRangeModel('TEST123', $expectedStartHour, $expectedEndHour);

        $imagine = new Imagine();
        $image = $imagine->open($graphFilename);

        $hourCacheMock = $this->createMock(HourRangeCacheInterface::class);
        $hourCacheMock
            ->expects($this->never())
            ->method('get')
        ;

        $hourCacheMock
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo('TEST123'), $this->equalTo($expectedHourRange))
        ;

        $hourRange = (new HourRange($hourCacheMock))->calculateAndCache($image, 'TEST123');

        $this->assertEquals($expectedHourRange, $hourRange);
    }

    public static function graphFilenameProvider(): array
    {
        return [
            [__DIR__ . '/../../graph/hamburg1.png', 6, 21],
            [__DIR__ . '/../../graph/lueneburg5.png', 7, 18],
        ];
    }
}
