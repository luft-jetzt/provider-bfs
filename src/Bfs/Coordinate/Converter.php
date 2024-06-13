<?php declare(strict_types=1);

namespace App\Bfs\Coordinate;

use Couchbase\Coordinate;

class Converter
{
    private function __construct()
    {

    }

    public static function convert(string $coordinateString): array
    {
        preg_match_all('/(\d+)°(\d+)\'(\d+)"/', $coordinateString, $matches, PREG_SET_ORDER);

        $latDeg = (float) $matches[0][1];
        $latMin = (float) $matches[0][2];
        $latSec = (float) $matches[0][3];

        $lonDeg = (float) $matches[1][1];
        $lonMin = (float) $matches[1][2];
        $lonSec = (float) $matches[1][3];

        $decimalLatitude = $latDeg + ($latMin / 60) + ($latSec / 3600);
        $decimalLongitude = $lonDeg + ($lonMin / 60) + ($lonSec / 3600);

        return [
            'lat' => round($decimalLatitude, 10),
            'lng' => round($decimalLongitude, 10),
        ];
    }
}