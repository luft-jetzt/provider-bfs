<?php

declare(strict_types=1);

namespace App\Bfs\Graph;

use App\Bfs\Exception\NoPointException;
use Imagine\Exception\InvalidArgumentException;
use Imagine\Image\ImageInterface;
use Imagine\Image\Palette\Color\RGB;
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

        for ($x = GraphDimensions::GRAPH_RIGHT_EDGE; $x >= 0; --$x) {
            try {
                $point = new ImaginePoint($x, $height - GraphDimensions::X_AXIS_OFFSET);
            } catch (InvalidArgumentException $invalidArgumentException) {
                throw new NoPointException('Could not find current point in image.');
            }

            /** @var RGB $color */
            $color = $image->getColorAt($point);

            if ($color->getRed() !== $color->getGreen() || $color->getRed() !== $color->getBlue() || $color->getGreen() !== $color->getBlue()) {
                break;
            }
        }

        for ($y = $height - GraphDimensions::X_AXIS_OFFSET; $y > 0; --$y) {
            try {
                $point = new ImaginePoint($x, $y);
            } catch (InvalidArgumentException $invalidArgumentException) {
                throw new NoPointException('Could not find current point in image.');
            }

            /** @var RGB $color */
            $color = $image->getColorAt($point);

            if ($color->getRed() === $color->getGreen() && $color->getRed() === $color->getBlue()) {
                ++$y;

                break;
            }
        }

        try {
            $point = new ImaginePoint($x, $y);
        } catch (InvalidArgumentException $invalidArgumentException) {
            throw new NoPointException('Could not find current point in image.');
        }

        return $point;
    }
}
