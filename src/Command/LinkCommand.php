<?php

namespace App\Command;

use App\Bfs\Website\StationLinkExtractorInterface;
use App\Bfs\Website\StationPageParserInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'LinkCommand',
    description: 'Add a short description for your command',
)]
class LinkCommand extends Command
{
    public function __construct(private StationLinkExtractorInterface $stationLinkExtractor, private StationPageParserInterface $stationPageParser)
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
        $linkList = $this->stationLinkExtractor->parseStationLinks();

        foreach ($linkList as $link) {
            $url = sprintf('https://www.bfs.de/%s', $link['href']);

            $stationModel = $this->stationPageParser->parse($url);

            dd($stationModel);
        }

// Gib das Array der Links aus
        print_r($links);
        return Command::SUCCESS;
    }
}
