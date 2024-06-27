<?php declare(strict_types=1);

namespace App\Bfs\Graph;

use Imagine\Image\ImageInterface;
use Imagine\Image\Point as ImaginePoint;

class Point
{
    private function __construct()
    {

    }

    public static function detectCurrentPoint(ImageInterface $image): ImaginePoint
    {
        $size = $image->getSize();
        $width = $size->getWidth();
        $height = $size->getHeight();

        for ($x = 575; $x >= 0; --$x) {
            $point = new ImaginePoint($x,  $height - 49);
            $color = $image->getColorAt($point);

            if ($color->getRed() !== $color->getGreen() || $color->getRed() !== $color->getBlue() || $color->getGreen() !== $color->getBlue()) {
                break;
            }
        }

        for ($y = $height - 49; $y > 0; --$y) {
            $point = new ImaginePoint($x, $y);
            $color = $image->getColorAt($point);

            if ($color->getRed() == $color->getGreen() && $color->getRed() == $color->getBlue()) {
                ++$y;

                break;
            }
        }

        return new ImaginePoint($x, $y);
    }
}
