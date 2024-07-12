<?php declare(strict_types=1);

namespace App\Command\HourRange;

use App\Bfs\Graph\HourRange\HourRangeModel;
use App\Bfs\Station\StationModel;
use App\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'luft:hour-range:list-cache',
    description: 'List cached hour ranges',
)]
class ListCommand extends AbstractCommand
{
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
            $stationCode = $station->getStationCode();

            $hourRange = $this->hourRangeCache->get($stationCode);

            $resultList[$stationCode] = $hourRange;
        }

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

        return Command::SUCCESS;
    }
}
