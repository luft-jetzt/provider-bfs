<?php

declare(strict_types=1);

namespace App\Bfs\Website;

interface StationLinkExtractorInterface
{
    public const PAGE_URL = 'https://www.bfs.de/DE/themen/opt/uv/uv-index/aktuelle-tagesverlaeufe/aktuelle-tagesverlaeufe.html';
    public const HOSTNAME = 'https://www.bfs.de/';

    /** @return array<int, PageLinkModel> */
    public function parseStationLinks(): array;
}
