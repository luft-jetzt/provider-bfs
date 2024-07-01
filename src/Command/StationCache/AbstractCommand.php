<?php declare(strict_types=1);

namespace App\Command\StationCache;

use App\Bfs\Cache\CacheInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{
    protected function createCache(): AdapterInterface
    {
        return new FilesystemAdapter(CacheInterface::CACHE_NAMESPACE, CacheInterface::CACHE_TTL, CacheInterface::CACHE_DIRECTORY);
    }

    protected function getStationList(): array
    {
        $cache = new FilesystemAdapter(CacheInterface::CACHE_NAMESPACE, CacheInterface::CACHE_TTL);
        $item = $cache->getItem(CacheInterface::CACHE_KEY);
        return $item->get();
    }

    public function cacheStationList(array $stationList): void
    {
        $cache = $this->createCache();
        $cacheItem = $cache->getItem(CacheInterface::CACHE_KEY);
        $cacheItem->set($stationList);
        $cache->save($cacheItem);
    }
}
