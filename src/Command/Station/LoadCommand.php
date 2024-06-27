<?php declare(strict_types=1);

namespace App\Command\Station;

use App\Command\StationCache\AbstractCommand;
use Caldera\LuftApiBundle\Api\StationApiInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'luft:station:load',
    description: 'Add a short description for your command',
)]
class LoadCommand extends AbstractCommand
{
    public function __construct(private readonly StationApiInterface $stationApi)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $stationList = $this->getStationList();

        $io->info(sprintf('There are %d stations saved in cache', count($stationList)));

        $io->progressStart(count($stationList));

        foreach ($stationList as $station) {
            $this->stationApi->putStations([$station]);

            $io->progressAdvance();
        }

        $io->progressFinish();

        return Command::SUCCESS;
    }
}
