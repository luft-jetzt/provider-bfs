<?php

declare(strict_types=1);

namespace App\Bfs\Graph;

use Imagine\Image\ImageInterface;
use Imagine\Image\Point as ImaginePoint;

class Maintenance
{
    private const array POINT_LISTS = [
        [
            [247, 80],
            [417, 80],
            [247, 109],
            [417, 109],
        ], [
            [247, 120],
            [417, 120],
            [247, 149],
            [417, 149],
        ],
    ];

    private function __construct()
    {
    }

    public static function isMaintenance(ImageInterface $image): bool
    {
        $black = $image->palette()->color('000');

        foreach (self::POINT_LISTS as $pointList) {
            // The counter must be evaluated per point list. A maintenance box is
            // only present when a single list has all of its edges black;
            // accumulating across lists produced false positives (e.g. 2 black
            // edges in each list).
            $edgeCounter = 0;

            foreach ($pointList as $point) {
                $imagePoint = new ImaginePoint($point[0], $point[1]);

                if ($image->getColorAt($imagePoint) === $black) {
                    ++$edgeCounter;
                }

                if (GraphDimensions::MAINTENANCE_EDGE_COUNT === $edgeCounter) {
                    return true;
                }
            }
        }

        return false;
    }
}
