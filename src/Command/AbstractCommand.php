<?php

declare(strict_types=1);

namespace App\Command;

use App\Bfs\Cache\CacheInterface;
use App\Bfs\Website\StationModel;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{
    public function __construct(private readonly AdapterInterface $stationCache)
    {
        parent::__construct();
    }

    /** @return array<string, StationModel> */
    protected function getStationList(): array
    {
        $item = $this->stationCache->getItem(CacheInterface::CACHE_KEY);

        return $item->get();
    }

    /** @param array<string, StationModel> $stationList */
    public function cacheStationList(array $stationList): void
    {
        $cacheItem = $this->stationCache->getItem(CacheInterface::CACHE_KEY);
        $cacheItem->set($stationList);
        $this->stationCache->save($cacheItem);
    }
}
