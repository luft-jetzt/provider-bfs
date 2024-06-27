<?php declare(strict_types=1);

namespace App\Bfs\Graph;

use Imagine\Image\ImageInterface;

class MaxUvIndex
{
    private function __construct()
    {

    }

    public static function detectMaxUvIndex(ImageInterface $image): float
    {
        $sizeY = Scale::sizeY($image);

        $maxUvIndex = floor(($sizeY - 2) / 2);

        return $maxUvIndex;
    }
}
