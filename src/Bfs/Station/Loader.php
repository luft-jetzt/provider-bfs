<?php declare(strict_types=1);

namespace App\Bfs\Station;

use App\Bfs\Website\StationLinkExtractorInterface;
use App\Bfs\Website\StationPageParserInterface;

class Loader implements LoaderInterface
{
    public function __construct(
        private readonly StationLinkExtractorInterface $linkExtractor,
        private readonly StationPageParserInterface $pageParser
    )
    {

    }
    public function load(): array
    {

    }
}