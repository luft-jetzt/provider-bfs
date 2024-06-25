<?php

namespace App\Bfs\Graph;

use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

class MaxUvIndexDetector
{
    private function __construct()
    {

    }

    public static function detectMaxUvIndex(ImageInterface $image): float
    {
        $size = $image->getSize();
        $height = $size->getHeight();

        $counter = 1;

        for ($y = $height - 55; $y > 55; --$y) {
            $point = new Point(81, $y);
            $color = $image->getColorAt($point);

            $image->draw()->dot($point, $image->palette()->color('f00'));


            if ($color->getRed() < 230 || $color->getGreen() < 230 || $color->getRed() < 230) {
                ++$counter;
            }
        }
        //$image->save('tmp4-redline.png');

        $maxUvIndex = floor(($counter - 1) / 2);

        return $maxUvIndex;
    }
}
