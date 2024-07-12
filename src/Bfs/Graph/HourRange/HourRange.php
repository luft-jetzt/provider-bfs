<?php declare(strict_types=1);

namespace App\Bfs\Graph\HourRange;

use App\Bfs\Cache\HourRangeCacheInterface;
use App\Bfs\Graph\Scale;
use Imagine\Image\ImageInterface;

class HourRange
{
    private const array RANGE_MAPPING = [
        12 => [7, 18],
        16 => [6, 21],
    ];

    public function __construct(private readonly HourRangeCacheInterface $cache)
    {

    }

    public function getCachedHourRange(string $stationCode): ?HourRangeModel
    {
        return $this->cache->get($stationCode);
    }

    public function calculateAndCache(ImageInterface $image, string $stationCode): HourRangeModel
    {
        $xScaleWidth = Scale::sizeX($image);

        if (array_key_exists($xScaleWidth, self::RANGE_MAPPING)) {
            $hourRange = new HourRangeModel(
                $stationCode,
                self::RANGE_MAPPING[$xScaleWidth][0],
                self::RANGE_MAPPING[$xScaleWidth][1]
            );

            $this->cache->save($stationCode, $hourRange);

            return $hourRange;
        }

        throw new \Exception(sprintf('Invalid scale width %d for hour range.', $xScaleWidth));
    }
}
