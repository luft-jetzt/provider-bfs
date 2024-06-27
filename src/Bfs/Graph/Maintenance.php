<?php declare(strict_types=1);

namespace App\Bfs\Graph;

use Imagine\Image\ImageInterface;
use Imagine\Image\Point as ImaginePoint;
class Maintenance
{
    private const array POINT_LIST = [
        [247, 80],
        [417, 80],
        [247, 109],
        [417, 109],
    ];

    private function __construct()
    {

    }

    public static function isMaintenance(ImageInterface $image): bool
    {
        $black = $image->palette()->color('000');

        foreach (self::POINT_LIST as $coord) {
            $point = new ImaginePoint($coord[0], $coord[1]);

            if ($image->getColorAt($point) !== $black) {
                return false;
            }
        }

        return true;
    }
}
