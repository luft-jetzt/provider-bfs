<?php

declare(strict_types=1);

namespace App\Bfs\Graph;

use Imagine\Image\ImageInterface;

class StepSize
{
    private function __construct()
    {
    }

    public static function detectStepSize(ImageInterface $image): float
    {
        $maxUvIndex = MaxUvIndex::detectMaxUvIndex($image);

        $size = round(GraphDimensions::GRAPH_HEIGHT / ($maxUvIndex * 2));

        return $size;
    }
}
