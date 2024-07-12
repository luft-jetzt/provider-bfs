<?php declare(strict_types=1);

namespace App\Bfs\Cache;

use App\Bfs\Graph\HourRange\HourRangeModel;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class HourRangeCache extends AbstractCache implements HourRangeCacheInterface
{
    public function __construct()
    {
        $this->cache = new FilesystemAdapter(
            self::CACHE_NAMESPACE,
            self::CACHE_TTL,
            self::CACHE_DIRECTORY
        );
    }

    public function get(string $stationCode): ?HourRangeModel
    {
        $cacheItem = $this->cache->getItem($this->key($stationCode));

        return $cacheItem->get();
    }

    public function save(string $stationCode, HourRangeModel $hourRange): void
    {
        $cacheItem = $this->cache->getItem($this->key($stationCode));
        $cacheItem->set($hourRange);

        $this->cache->save($cacheItem);
    }

    private function key(string $stationCode): string
    {
        return sprintf('%s_%s', self::CACHE_KEY_PREFIX, strtolower($stationCode));
    }
}
