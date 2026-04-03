<?php

declare(strict_types=1);

namespace App\Tests\Unit\Fetcher;

use App\Bfs\Fetcher\ValueFetcher;
use App\Bfs\Website\StationModel;
use Caldera\LuftModel\Model\Value;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ValueFetcherTest extends TestCase
{
    private function createValueFetcher(): ValueFetcher
    {
        $httpClient = $this->createStub(HttpClientInterface::class);

        return new ValueFetcher($httpClient);
    }

    #[DataProvider('graphFilenameProvider')]
    public function testValueFetcher(string $currentImageUrl, float $expectedUvIndex, \DateTime $expectedDateTime, \DateTime $currentDateTime): void
    {
        $expectedValue = new Value();
        $expectedValue
            ->setPollutant('UVIndex')
            ->setStationCode('TEST123')
            ->setValue($expectedUvIndex)
            ->setDateTime($expectedDateTime);

        $valueFetcher = $this->createValueFetcher();
        $valueFetcher->setNow($currentDateTime);

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
                __DIR__.'/../../graph/boesel.png',
                2.7,
                self::createDateTime(9, 3),
                self::createDateTime(9, 3),
            ],
            [
                __DIR__.'/../../graph/friedrichshafen1.png',
                0.9,
                self::createDateTime(10, 0),
                self::createDateTime(9, 3),
            ],
            [
                __DIR__.'/../../graph/friedrichshafen2.png',
                0.6,
                self::createDateTime(20, 58),
                self::createDateTime(20, 58),
            ],
            [
                __DIR__.'/../../graph/hamburg1.png',
                3,
                self::createDateTime(17, 50),
                self::createDateTime(9, 3),
            ],
            [
                __DIR__.'/../../graph/hamburg2.png',
                4.2,
                self::createDateTime(9, 54),
                self::createDateTime(9, 3),
            ],
            [
                __DIR__.'/../../graph/lueneburg1.png',
                0.6,
                self::createDateTime(18, 10),
                self::createDateTime(9, 3),
            ],
            [
                __DIR__.'/../../graph/lueneburg2.png',
                5.3,
                self::createDateTime(14, 9),
                self::createDateTime(9, 3),
            ],
            [
                __DIR__.'/../../graph/lueneburg3.png',
                4.6,
                self::createDateTime(15, 9),
                self::createDateTime(9, 3),
            ],
            [
                __DIR__.'/../../graph/lueneburg4.png',
                5.4,
                self::createDateTime(15, 0),
                self::createDateTime(9, 3),
            ],
            [
                __DIR__.'/../../graph/schneefernhaus1.png',
                4.4,
                self::createDateTime(9, 50),
                self::createDateTime(9, 3),
            ],
            [
                __DIR__.'/../../graph/schneefernhaus2.png',
                1.3,
                self::createDateTime(15, 10),
                self::createDateTime(9, 3),
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

        $valueFetcher = $this->createValueFetcher();

        $this->assertNull($valueFetcher->fromStation($stationModel));
    }

    public static function maintenanceFilenameProvider(): array
    {
        return [
            [__DIR__.'/../../graph/sanktaugustin.png'],
            [__DIR__.'/../../graph/hamburg3.png'],
        ];
    }

    #[DataProvider('emptyFilenameProvider')]
    public function testEmpty(string $currentImageUrl): void
    {
        $stationModel = new StationModel();
        $stationModel
            ->setCurrentImageUrl($currentImageUrl)
            ->setStationCode('TEST123');

        $valueFetcher = $this->createValueFetcher();

        $this->assertNull($valueFetcher->fromStation($stationModel));
    }

    public static function emptyFilenameProvider(): array
    {
        return [
            [__DIR__.'/../../graph/melpitz.png'],
        ];
    }

    private static function createDateTime(int $hour, int $minute): \DateTime
    {
        return (new \DateTime('now', new \DateTimeZone('Europe/Berlin')))->setTime($hour, $minute);
    }
}
