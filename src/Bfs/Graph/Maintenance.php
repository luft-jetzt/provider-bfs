<?php declare(strict_types=1);

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
        ]
    ];

    private function __construct()
    {

    }

    public static function isMaintenance(ImageInterface $image): bool
    {
        $black = $image->palette()->color('000');

        $edgeCounter = 0;

        foreach (self::POINT_LISTS as $pointList)
            foreach ($pointList as $point) {
                $imagePoint = new ImaginePoint($point[0], $point[1]);

                if ($image->getColorAt($imagePoint) === $black) {
                    ++$edgeCounter;
                }

                if (4 === $edgeCounter) {
                    return true;
                }
            }

        return false;
    }
}
