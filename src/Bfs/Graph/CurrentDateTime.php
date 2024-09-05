<?php declare(strict_types=1);

namespace App\Bfs\Graph;

use App\Bfs\Exception\NoPointException;
use App\Bfs\Graph\HourRange\HourRange;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Imagine\Image\ImageInterface;

class CurrentDateTime
{
    private const TIMEZONE_IDENTIFIER = 'Europe/Berlin';
    private const int GRAPH_WIDTH = 495;
    private const int GRAPH_MARGIN_LEFT = 81;

    private function __construct()
    {

    }

    public static function calculate(ImageInterface $image): ?Carbon
    {
        $hourRange = HourRange::calculate($image);

        try {
            $currentPoint = Point::detectCurrentPoint($image);
        } catch (NoPointException $exception) {
            return null;
        }

        $currentX = $currentPoint->getX() - self::GRAPH_MARGIN_LEFT;

        $totalHours = $hourRange->getEndHour() - $hourRange->getStartHour();

        $hoursPerPixel = $totalHours / self::GRAPH_WIDTH;

        $timeInHours = $hourRange->getStartHour() + ($currentX * $hoursPerPixel);

        $hours = floor($timeInHours);
        $minutes = ($timeInHours - $hours) * 60;

        $timezone = new CarbonTimeZone(self::TIMEZONE_IDENTIFIER);

        return Carbon::create(null, null, null, (int) floor($timeInHours), (int) floor($minutes), 0, $timezone);
    }
}
