<?php declare(strict_types=1);

namespace App\Bfs\Website;

use App\Bfs\Station\StationModel;

interface StationPageParserInterface
{
    public function parse(string $url): StationModel;
}
