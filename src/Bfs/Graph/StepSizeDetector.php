<?php declare(strict_types=1);

namespace App\Bfs\Graph;

use Imagine\Image\ImageInterface;

class StepSizeDetector
{
    private function __construct()
    {

    }

    public static function detectStepSize(ImageInterface $image): float
    {
        $maxUvIndex = MaxUvIndexDetector::detectMaxUvIndex($image);

        $size = round(385 / ($maxUvIndex * 2));

        return $size;
    }
}
