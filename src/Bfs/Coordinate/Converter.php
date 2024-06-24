<?php declare(strict_types=1);

namespace App\Bfs\Coordinate;

class Converter
{
    private function __construct()
    {

    }

    public static function convert(string $coordinateString): Coord
    {
        preg_match_all('/(\d+)°(\d+)\'((\d+)")?/', $coordinateString, $matches, PREG_SET_ORDER);

        $latDeg = (float) $matches[0][1];
        $latMin = (float) $matches[0][2];
        $latSec = array_key_exists(3, $matches[0]) ? (float) $matches[0][3] : 0;

        $lonDeg = (float) $matches[1][1];
        $lonMin = (float) $matches[1][2];
        $lonSec = array_key_exists(3, $matches[1]) ? (float) $matches[1][3] : 0;

        $decimalLatitude = $latDeg + ($latMin / 60) + ($latSec / 3600);
        $decimalLongitude = $lonDeg + ($lonMin / 60) + ($lonSec / 3600);

        $latitude = round($decimalLatitude, 10);
        $longitude = round($decimalLongitude, 10);

        return new Coord($latitude, $longitude);
    }
}