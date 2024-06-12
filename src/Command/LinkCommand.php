<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

#[AsCommand(
    name: 'LinkCommand',
    description: 'Add a short description for your command',
)]
class LinkCommand extends Command
{
    public function __construct()
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
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://www.bfs.de/DE/themen/opt/uv/uv-index/aktuelle-tagesverlaeufe/aktuelle-tagesverlaeufe.html');
        $htmlContent = $response->getContent();

// Initialisiere den Crawler mit dem HTML-Inhalt
        $crawler = new Crawler($htmlContent);

// Finde alle LI-Knoten innerhalb der UL-Liste und extrahiere die Links
        $links = $crawler->filter('#main #content ul li a')->each(function (Crawler $node) {
            return [
                'href' => $node->attr('href'),
                'caption' => $node->text(),
            ];
        });

// Gib das Array der Links aus
        print_r($links);
        return Command::SUCCESS;
    }
}
