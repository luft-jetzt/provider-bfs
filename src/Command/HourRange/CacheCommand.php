<?php declare(strict_types=1);

namespace App\Command\HourRange;

use App\Bfs\Cache\HourRangeCacheInterface;
use App\Bfs\Cache\StationCacheInterface;
use App\Bfs\Graph\HourRange\HourRange;
use App\Bfs\Graph\HourRange\HourRangeModel;
use App\Bfs\Station\StationModel;
use App\Command\AbstractCommand;
use Caldera\LuftModel\Model\Value;
use Imagine\Gd\Imagine;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;

#[AsCommand(
    name: 'luft:hour-range:cache',
    description: 'Cache hour ranges from graphs',
)]
class CacheCommand extends AbstractCommand
{
    public function __construct(
        StationCacheInterface $stationCache,
        HourRangeCacheInterface $hourRangeCache,
        private readonly HourRange $hourRange,
    )
    {
        parent::__construct($stationCache, $hourRangeCache);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $stationList = $this->stationCache->getList();

        $io->info(sprintf('There are %d stations saved in cache', count($stationList)));

        if ($output->isVerbose()) {
            $io->progressStart(count($stationList));
        }

        $resultList = [];

        /** @var StationModel $station */
        foreach ($stationList as $station) {
            try {
                $binaryImagecontent = $this->loadImageContent($station->getCurrentImageUrl());
                $imagine = new Imagine();
                $image = $imagine->load($binaryImagecontent);
            } catch (\Exception $exception) {
                continue;
            }

            $stationCode = $station->getStationCode();

            $hourRange = $this->hourRange->calculateAndCache($image, $stationCode);

            $resultList[$stationCode] = $hourRange;

            if ($output->isVerbose()) {
                $io->progressAdvance();
            }
        }

        if ($output->isVerbose()) {
            $io->progressFinish();

            $io->table(['Station Code', 'Station Title', 'Hour start', 'Hour end'], array_map(function(HourRangeModel $hourRange) use ($stationList): array
            {
                $stationCode = $hourRange->getStationCode();
                $stationTitle = $stationList[$stationCode]->getTitle();

                return [
                    $stationCode,
                    $stationTitle,
                    (string) $hourRange->getStartHour(),
                    (string) $hourRange->getEndHour(),
                ];
            }, $resultList));
        }

        return Command::SUCCESS;
    }

    protected function loadImageContent(string $url): string
    {
        if (str_starts_with($url, 'https://')) {
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', $url);

            return $response->getContent();
        }

        return file_get_contents($url);
    }
}
