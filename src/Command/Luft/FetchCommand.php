<?php

declare(strict_types=1);

namespace App\Command\Luft;

use App\Bfs\Fetcher\ValueFetcherInterface;
use App\Bfs\Website\StationModel;
use App\Command\AbstractCommand;
use Caldera\LuftApiBundle\Api\ValueApi;
use Caldera\LuftModel\Model\Value;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'luft:fetch',
    description: 'Fetch current uv values and push to luft api',
)]
class FetchCommand extends AbstractCommand
{
    public function __construct(
        AdapterInterface $stationCache,
        private readonly ValueFetcherInterface $valueFetcher,
        private readonly ValueApi $valueApi,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct($stationCache);
    }

    protected function configure(): void
    {
        $this->addArgument('station-code', InputArgument::OPTIONAL, 'Specify station code to fetch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $stationList = $this->getStationList();

        if (!$stationList) {
            $io->error('No station list found in cache. Please load cache before fetching values.');

            return Command::FAILURE;
        }

        if ($input->getArgument('station-code')) {
            $stationList = $this->processStationList($input, $stationList);
        }

        $valueList = [];
        $failedStations = [];

        if ($output->isVerbose()) {
            $io->progressStart(count($stationList));
        }

        /** @var StationModel $station */
        foreach ($stationList as $station) {
            try {
                $value = $this->valueFetcher->fromStation($station);
            } catch (\Exception $exception) {
                $failedStations[] = $station->getStationCode();

                $this->logger->error('Error fetching value for station {station} ({code}): {message}', [
                    'station' => $station->getTitle(),
                    'code' => $station->getStationCode(),
                    'message' => $exception->getMessage(),
                    'exception' => $exception,
                ]);

                if ($output->isVerbose()) {
                    $io->warning(sprintf('Station %s (%s): %s', $station->getTitle(), $station->getStationCode(), $exception->getMessage()));
                }

                continue;
            }

            if ($value instanceof Value) {
                $valueList[$station->getStationCode()] = $value;
            }

            if ($output->isVerbose()) {
                $io->progressAdvance();
            }
        }

        if ($output->isVerbose()) {
            $io->progressFinish();

            $io->table(['Station Code', 'Station Title', 'Date Time', 'UV Index'], array_map(function (Value $value) use ($stationList): array {
                $stationTitle = $stationList[$value->getStationCode()]->getTitle();

                return [
                    $value->getStationCode(),
                    $stationTitle,
                    $value->getDateTime()->format('Y-m-d H:i:s'),
                    $value->getValue(),
                ];
            }, $valueList));
        }

        if ($failedStations) {
            $io->warning(sprintf('%d station(s) failed: %s', count($failedStations), implode(', ', $failedStations)));
        }

        $this->valueApi->putValues($valueList);

        return Command::SUCCESS;
    }

    /**
     * @param array<string, StationModel> $stationList
     *
     * @return array<string, StationModel>
     */
    protected function processStationList(InputInterface $input, array $stationList): array
    {
        $specifiedStationCode = $input->getArgument('station-code') ?? '';
        $specifiedStationCodeList = explode(',', $specifiedStationCode);
        $specifiedStationCodeList = array_flip($specifiedStationCodeList);

        return array_intersect_key($stationList, $specifiedStationCodeList);
    }
}
