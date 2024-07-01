<?php declare(strict_types=1);

namespace App\Command\StationCache;

use App\Bfs\Station\Namer;
use App\Bfs\Website\StationLinkExtractorInterface;
use App\Bfs\Website\StationPageParserInterface;
use App\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'luft:station:cache',
    description: 'Add a short description for your command',
)]
class SaveCommand extends AbstractCommand
{
    public function __construct(
        private readonly StationLinkExtractorInterface $linkExtractor,
        private readonly StationPageParserInterface $pageParser,
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

        $io->info('Loading html page from bfs');

        $resultList = $this->linkExtractor->parseStationLinks();

        $io->info(sprintf('Found %d results', count($resultList)));

        $io->progressStart(count($resultList));

        $stationList = [];

        foreach ($resultList as $pageLinkModel) {
            $station = $this->pageParser->parse($pageLinkModel->getUrl());

            $stationCode = Namer::generate($station);
            $station->setStationCode($stationCode);
            $stationList[$stationCode] = $station;

            $io->progressAdvance();
        }

        $io->progressFinish();

        $this->cacheStationList($stationList);

        $io->info(sprintf('Saved %d to cache', count($stationList)));

        return Command::SUCCESS;
    }
}
