<?php declare(strict_types=1);

namespace App\Bfs\Website;

interface StationPageParserInterface
{
    public function parse(string $url): StationModel;
}
