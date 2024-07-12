<?php declare(strict_types=1);

namespace App\Bfs\Cache;

interface StationCacheInterface extends CacheInterface
{
    public const string CACHE_NAMESPACE = 'station_cache';
    public const string CACHE_KEY = 'station_list';
    public const int CACHE_TTL = 60 * 60 * 18;

    public function getList(): ?array;
    public function saveList(array $stationList): void;
}
