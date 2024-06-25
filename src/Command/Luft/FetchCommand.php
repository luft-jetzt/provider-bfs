<?php

namespace App\Command\Luft;

use App\Bfs\Cache\CacheInterface;
use App\Bfs\Fetcher\ValueFetcherInterface;
use App\Bfs\Website\StationLinkExtractorInterface;
use App\Bfs\Website\StationModel;
use App\Bfs\Website\StationPageParserInterface;
use Caldera\LuftModel\Model\Value;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'luft:fetch',
    description: 'Add a short description for your command',
)]
class FetchCommand extends Command
{
    public function __construct(private readonly ValueFetcherInterface $valueFetcher)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('station-code', InputArgument::OPTIONAL, 'Specify station code to fetch');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $cache = new FilesystemAdapter(CacheInterface::CACHE_NAMESPACE, CacheInterface::CACHE_TTL);
        $cacheItem = $cache->getItem(CacheInterface::CACHE_KEY);
        $stationList = $cacheItem->get();

        if ($input->getArgument('station-code')) {
            $stationList = $this->processStationList($input, $stationList);
        }

        $valueList = [];

        $io->progressStart(count($stationList));

        /** @var StationModel $station */
        foreach ($stationList as $station) {
            $value = $this->valueFetcher->fromStation($station);
            $valueList[$station->getStationCode()] = $value;

            $io->progressAdvance();
        }

        $io->progressFinish();

        if ($output->isVerbose()) {
            $io->table(['Station Code', 'Station Title', 'Date Time', 'UV Index'], array_map(function(Value $value) use ($stationList): array
            {
                $stationTitle = $stationList[$value->getStationCode()]->getTitle();
                return [
                    $value->getStationCode(),
                    $stationTitle,
                    $value->getDateTime()->format('Y-m-d H:i:s'),
                    $value->getValue()
                ];
            }, $valueList));
        }

        return Command::SUCCESS;
    }

    protected function processStationList(InputInterface $input, array $stationList): array
    {
        $specifiedStationCode = $input->getArgument('station-code') ?? '';
        $specifiedStationCodeList = explode(',', $specifiedStationCode);
        $specifiedStationCodeList = array_flip($specifiedStationCodeList);

        return array_intersect_key($stationList, $specifiedStationCodeList);
    }
}
