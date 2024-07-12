<?php declare(strict_types=1);

namespace App\Bfs\Graph\HourRange;

use App\Bfs\Cache\CacheInterface;
use App\Bfs\Cache\HourRangeCacheInterface;
use App\Bfs\Graph\Scale;
use Imagine\Image\ImageInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class HourRange
{
    private const array RANGE_MAPPING = [
        12 => [7, 18],
        16 => [6, 21],
    ];

    private FilesystemAdapter $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter(
            HourRangeCacheInterface::CACHE_NAMESPACE,
            HourRangeCacheInterface::CACHE_TTL,
            HourRangeCacheInterface::CACHE_DIRECTORY
        );
    }

    public function getCachedHourRange(string $stationCode): ?HourRangeModel
    {
        $item = $this->cache->getItem(CacheInterface::CACHE_KEY);
        return $item->get();
    }

    protected function cacheHourRange(HourRangeModel $hourRangeModel)
    {

    }
    public function calculateAndCache(ImageInterface $image, string $stationCode): HourRangeModel
    {
        $xScaleWidth = Scale::sizeX($image);

        if (array_key_exists($xScaleWidth, self::RANGE_MAPPING)) {
            $hourRange = new HourRangeModel(
                self::RANGE_MAPPING[$xScaleWidth][0],
                self::RANGE_MAPPING[$xScaleWidth][1]
            );

            $cacheItem = $this->cache->getItem(CacheInterface::CACHE_KEY);
            $cacheItem->set($hourRange);
            $this->cache->save($cacheItem);

            return $hourRange;
        }

        throw new \Exception(sprintf('Invalid scale width %d for hour range.', $xScaleWidth));
    }
}
