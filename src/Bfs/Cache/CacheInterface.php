<?php declare(strict_types=1);

namespace App\Bfs\Cache;

interface CacheInterface
{
    public const string CACHE_NAMESPACE = 'station_cache';
    public const string CACHE_KEY = 'stations';
    public const int CACHE_TTL = 0;
}