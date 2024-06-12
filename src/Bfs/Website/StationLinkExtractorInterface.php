<?php declare(strict_types=1);

namespace App\Bfs\Website;

interface StationLinkExtractorInterface
{
    const PAGE_URL = 'https://www.bfs.de/DE/themen/opt/uv/uv-index/aktuelle-tagesverlaeufe/aktuelle-tagesverlaeufe.html';

    public function parseStationLinks(): array;
}
