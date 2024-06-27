<?php declare(strict_types=1);

namespace App\Bfs\Graph;

use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

class Scale
{
    private const int Y_PADDING_TOP = 60;
    private const int Y_PADDING_BOTTOM = 65;
    private const int Y_PADDING_LEFT = 82;
    private const int X_PADDING_TOP = 50;
    private const int X_PADDING_LEFT = 80;
    private const int X_PADDING_RIGHT = 60;
    private const int X_THRESHOLD = 104;
    private const int Y_THRESHOLD = 113;

    private function __construct()
    {

    }

    public static function sizeY(ImageInterface $image): int
    {
        $size = $image->getSize();
        $height = $size->getHeight();

        $counter = 2;

        for ($y = $height - self::Y_PADDING_BOTTOM; $y > self::Y_PADDING_TOP; --$y) {
            $point = new Point(self::Y_PADDING_LEFT, $y);
            $color = $image->getColorAt($point);

            if ($color->getRed() === self::Y_THRESHOLD && $color->getGreen() === self::Y_THRESHOLD && $color->getRed() === self::Y_THRESHOLD) {
                ++$counter;

                $y -= 5; // jump five pixels to avoid detecting some grey artifacts as scale again
            }
        }

        return $counter;
    }

    public static function sizeX(ImageInterface $image): int
    {
        $size = $image->getSize();
        $width = $size->getWidth();

        $counter = 2;

        for ($x = $width - self::X_PADDING_RIGHT; $x > self::X_PADDING_LEFT; --$x) {
            $point = new Point($x, self::X_PADDING_TOP);
            $color = $image->getColorAt($point);

            if ($color->getRed() === self::X_THRESHOLD && $color->getGreen() === self::X_THRESHOLD && $color->getRed() === self::X_THRESHOLD) {
                ++$counter;

                $x -= 5; // jump five pixels to avoid detecting some grey artifacts as scale again
            }
        }

        return $counter;
    }
}
