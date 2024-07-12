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
    name: 'luft:hour-range:clear-cache',
    description: 'Clears hour range cache',
)]
class ClearCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $stationList = $this->stationCache->getList();

        $io->info(sprintf('There are %d stations saved in cache', count($stationList)));

        if ($output->isVerbose()) {
            $io->progressStart(count($stationList));
        }

        /** @var StationModel $station */
        foreach ($stationList as $station) {
            $this->hourRangeCache->remove($station->getStationCode());

            if ($output->isVerbose()) {
                $io->progressAdvance();
            }
        }

        if ($output->isVerbose()) {
            $io->progressFinish();
        }

        return Command::SUCCESS;
    }
}
