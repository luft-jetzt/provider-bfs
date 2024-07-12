<?php declare(strict_types=1);

namespace App\Bfs\Cache;

interface HourRangeCacheInterface
{
    public const string CACHE_NAMESPACE = 'hourrange_cache';
    public const string CACHE_KEY_PREFIX = 'station_hourrange_';
    public const int CACHE_TTL = 60 * 60 * 18;
    public const string CACHE_DIRECTORY = __DIR__.'/../../../var/cache/';
}
