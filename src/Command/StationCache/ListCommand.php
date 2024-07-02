<?php declare(strict_types=1);

namespace App\Command\StationCache;

use App\Bfs\Website\StationModel;
use App\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'luft:station:list-cache',
    description: 'Shows a list of cached stations',
)]
class ListCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $stationList = $this->getStationList();

        $io->info(sprintf('There are %d stations saved in cache', count($stationList)));

        $io->table(['Station Code', 'Title', 'Latitude', 'Longitude', 'Url', 'Image'], array_map(function(StationModel $station): array {
            return [
                $station->getStationCode(),
                $station->getTitle(),
                $station->getLatitude(),
                $station->getLongitude(),
                $station->getBfsPageUrl(),
                $station->getCurrentImageUrl()
            ];
        }, $stationList));

        return Command::SUCCESS;
    }
}
