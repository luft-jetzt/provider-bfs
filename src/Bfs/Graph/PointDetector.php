<?php

namespace App\Bfs\Graph;

use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

class PointDetector
{
    private function __construct()
    {

    }

    public static function detectCurrentPoint(ImageInterface $image): Point
    {
        $size = $image->getSize();
        $width = $size->getWidth();
        $height = $size->getHeight();

        for ($x = 575; $x >= 0; --$x) {
            $point = new Point($x,  $height - 49);
            $color = $image->getColorAt($point);

            if ($color->getRed() !== $color->getGreen() || $color->getRed() !== $color->getBlue() || $color->getGreen() !== $color->getBlue()) {
                break;
            }
        }

        for ($y = $height - 49; $y > 0; --$y) {
            $point = new Point($x, $y);
            $color = $image->getColorAt($point);

            //$image->draw()->dot($point, $image->palette()->color('f00'));
            //$image->save('tmp4-redline.png');

            if ($color->getRed() == $color->getGreen() && $color->getRed() == $color->getBlue()) {
                ++$y;

                break;
            }
        }

        return new Point($x, $y);
    }
}
