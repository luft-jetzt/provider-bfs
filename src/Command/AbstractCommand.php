<?php declare(strict_types=1);

namespace App\Command;

use App\Bfs\Cache\HourRangeCacheInterface;
use App\Bfs\Cache\StationCacheInterface;
use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{
    public function __construct(
        protected readonly StationCacheInterface $stationCache,
        protected readonly HourRangeCacheInterface $hourRangeCache
    )
    {
        parent::__construct();
    }
}
