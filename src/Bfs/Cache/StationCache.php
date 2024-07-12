<?php declare(strict_types=1);

namespace App\Bfs\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class StationCache extends AbstractCache implements StationCacheInterface
{
    public function __construct()
    {
        $this->cache = new FilesystemAdapter(
            self::CACHE_NAMESPACE,
            self::CACHE_TTL,
            self::CACHE_DIRECTORY
        );
    }

    public function getList(): ?array
    {
        $item = $this->cache->getItem(self::CACHE_KEY);
        return $item->get();
    }

    public function saveList(array $stationList): void
    {
        $cacheItem = $this->cache->getItem(self::CACHE_KEY);
        $cacheItem->set($stationList);
        $this->cache->save($cacheItem);
    }
}
