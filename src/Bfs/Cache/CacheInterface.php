<?php declare(strict_types=1);

namespace App\Bfs\Cache;

interface CacheInterface
{
    public const string CACHE_NAMESPACE = 'station_cache';
    public const string CACHE_KEY = 'station_list';
    public const int CACHE_TTL = 60 * 60 * 24 * 7;
    public const string CACHE_DIRECTORY = __DIR__.'/../../../var/cache/';
}
