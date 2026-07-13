<?php

declare(strict_types=1);

namespace App\Tests\Unit\Command;

use App\Bfs\Cache\CacheInterface;
use App\Bfs\Fetcher\ValueFetcherInterface;
use App\Bfs\Website\StationModel;
use App\Command\Luft\FetchCommand;
use Caldera\LuftApiBundle\Api\ValueApi;
use Caldera\LuftModel\Model\Value;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class FetchCommandTest extends TestCase
{
    public function testEmptyStationListFailsAndDoesNotPush(): void
    {
        $valueApi = $this->createMock(ValueApi::class);
        $valueApi->expects($this->never())->method('putValues');

        $command = new FetchCommand(
            $this->createStationCache([]),
            $this->createStub(ValueFetcherInterface::class),
            $valueApi,
            new NullLogger(),
        );

        $tester = new CommandTester($command);
        $exitCode = $tester->execute([]);

        $this->assertSame(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('No station list found in cache', $tester->getDisplay());
    }

    public function testStationCodeArgumentFiltersToASingleStation(): void
    {
        $stationList = [
            'CODE1' => $this->createStationModel('CODE1', 'Station One'),
            'CODE2' => $this->createStationModel('CODE2', 'Station Two'),
        ];

        $valueFetcher = $this->createMock(ValueFetcherInterface::class);
        $valueFetcher
            ->expects($this->once())
            ->method('fromStation')
            ->with($this->callback(static fn (StationModel $station): bool => 'CODE1' === $station->getStationCode()))
            ->willReturn($this->createValue('CODE1'));

        $captured = null;
        $valueApi = $this->createMock(ValueApi::class);
        $valueApi
            ->expects($this->once())
            ->method('putValues')
            ->with($this->callback(static function (array $valueList) use (&$captured): bool {
                $captured = $valueList;

                return true;
            }));

        $command = new FetchCommand(
            $this->createStationCache($stationList),
            $valueFetcher,
            $valueApi,
            new NullLogger(),
        );

        $tester = new CommandTester($command);
        $exitCode = $tester->execute(['station-code' => 'CODE1']);

        $this->assertSame(Command::SUCCESS, $exitCode);
        $this->assertIsArray($captured);
        $this->assertSame(['CODE1'], array_keys($captured));
    }

    /** @param array<string, StationModel> $stationList */
    private function createStationCache(array $stationList): AdapterInterface
    {
        $cache = new ArrayAdapter();

        $item = $cache->getItem(CacheInterface::CACHE_KEY);
        $item->set($stationList);
        $cache->save($item);

        return $cache;
    }

    private function createStationModel(string $stationCode, string $title): StationModel
    {
        $station = new StationModel();
        $station->setStationCode($stationCode);
        $station->setTitle($title);

        return $station;
    }

    private function createValue(string $stationCode): Value
    {
        $value = new Value();
        $value
            ->setStationCode($stationCode)
            ->setPollutant('UVIndex')
            ->setDateTime(new \DateTime('now', new \DateTimeZone('Europe/Berlin')))
            ->setValue(1.0);

        return $value;
    }
}
