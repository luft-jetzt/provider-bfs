<?php declare(strict_types=1);

namespace App\Tests\Unit\Fetcher;

use App\Bfs\Fetcher\ValueFetcher;
use App\Bfs\Website\StationModel;
use Caldera\LuftModel\Model\Value;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ValueFetcherTest extends TestCase
{
    #[DataProvider('graphFilenameProvider')]
    public function testValueFetcher(string $currentImageUrl, float $expectedUvIndex, Carbon $expectedDateTime, Carbon $currentDateTime): void
    {
        Carbon::setTestNow($currentDateTime);

        $expectedValue = new Value();
        $expectedValue
            ->setPollutant('UVIndex')
            ->setStationCode('TEST123')
            ->setValue($expectedUvIndex)
            ->setDateTime($expectedDateTime);

        $valueFetcher = new ValueFetcher();

        $stationModel = new StationModel();
        $stationModel
            ->setCurrentImageUrl($currentImageUrl)
            ->setStationCode('TEST123');

        $value = $valueFetcher->fromStation($stationModel);

        $this->assertEquals($expectedValue, $value);
    }

    public static function graphFilenameProvider(): array
    {
        return [
            [
                __DIR__ . '/../../graph/boesel.png',
                2.7,
                (new Carbon())->setTime(9, 3),
                (new Carbon())->setTime(9, 3),
            ],
            [
                __DIR__ . '/../../graph/friedrichshafen1.png',
                0.9,
                (new Carbon())->setTime(10, 0),
                (new Carbon())->setTime(9, 3),
            ],
            [
                __DIR__ . '/../../graph/friedrichshafen2.png',
                0,
                (new Carbon())->setTime(20, 58),
                (new Carbon())->setTime(21, 5),
            ],
            [
                __DIR__ . '/../../graph/hamburg1.png',
                3,
                (new Carbon())->setTime(17, 50),
                (new Carbon())->setTime(9, 3),
            ],
            [
                __DIR__ . '/../../graph/hamburg2.png',
                4.2,
                (new Carbon())->setTime(9, 54),
                (new Carbon())->setTime(9, 3),
            ],
            [
                __DIR__ . '/../../graph/lueneburg1.png',
                0.6,
                (new Carbon())->setTime(18, 10),
                (new Carbon())->setTime(9, 3),
            ],
            [
                __DIR__ . '/../../graph/lueneburg2.png',
                5.3,
                (new Carbon())->setTime(14, 9),
                (new Carbon())->setTime(9, 3),
            ],
            [
                __DIR__ . '/../../graph/lueneburg3.png',
                4.6,
                (new Carbon())->setTime(15, 9),
                (new Carbon())->setTime(9, 3),
            ],
            [
                __DIR__ . '/../../graph/lueneburg4.png',
                5.4,
                (new Carbon())->setTime(15, 0),
                (new Carbon())->setTime(9, 3),
            ],
            [
                __DIR__ . '/../../graph/schneefernhaus1.png',
                4.4,
                (new Carbon())->setTime(9, 50),
                (new Carbon())->setTime(9, 3),
            ],
            [
                __DIR__ . '/../../graph/schneefernhaus2.png',
                1.3,
                (new Carbon())->setTime(15, 10),
                (new Carbon())->setTime(9, 3),
            ],
        ];
    }

    #[DataProvider('maintenanceFilenameProvider')]
    public function testMaintenance(string $currentImageUrl): void
    {
        $stationModel = new StationModel();
        $stationModel
            ->setCurrentImageUrl($currentImageUrl)
            ->setStationCode('TEST123');

        $valueFetcher = new ValueFetcher();

        $this->assertNull($valueFetcher->fromStation($stationModel));
    }

    public static function maintenanceFilenameProvider(): array
    {
        return [
            [__DIR__ . '/../../graph/sanktaugustin.png'],
            [__DIR__ . '/../../graph/hamburg3.png'],
        ];
    }
}
