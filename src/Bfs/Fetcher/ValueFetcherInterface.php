<?php declare(strict_types=1);

namespace App\Bfs\Fetcher;

use App\Bfs\Graph\HourRange\StationModel;
use Caldera\LuftModel\Model\Value;

interface ValueFetcherInterface
{
    public function fromStation(StationModel $stationModel): ?Value;
}
