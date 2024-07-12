<?php declare(strict_types=1);

namespace App\Bfs\Cache;

use App\Bfs\Graph\HourRange\HourRangeModel;

interface HourRangeCacheInterface extends CacheInterface
{
    public const string CACHE_NAMESPACE = 'hourrange_cache';
    public const string CACHE_KEY_PREFIX = 'station_hourrange';
    public const int CACHE_TTL = 60 * 60 * 18;

    public function get(string $stationCode): ?HourRangeModel;
    public function save(string $stationCode, HourRangeModel $hourRange): void;
}
