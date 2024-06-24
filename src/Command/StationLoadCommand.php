<?php declare(strict_types=1);

namespace App\Command;

use App\Bfs\Station\Namer;
use App\Bfs\Website\StationLinkExtractorInterface;
use App\Bfs\Website\StationPageParserInterface;
use Caldera\LuftApiBundle\Api\StationApiInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'luft:station-load',
    description: 'Add a short description for your command',
)]
class StationLoadCommand extends Command
{
    public function __construct(
        private readonly StationLinkExtractorInterface $linkExtractor,
        private readonly StationPageParserInterface $pageParser,
        private readonly StationApiInterface $stationApi
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        dd($this->stationApi->getStations());

        $io->info('Loading html page from bfs');

        $resultList = $this->linkExtractor->parseStationLinks();

        $io->info(sprintf('Found %d results', count($resultList)));

        $stationList = [];

        foreach ($resultList as $stationResult) {
            $station = $this->pageParser->parse($stationResult['href']);

            $stationCode = Namer::generate($station);
            $station->setStationCode($stationCode);

            $stationList[] = $station;
        }



        return Command::SUCCESS;
    }
}
