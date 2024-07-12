<?php declare(strict_types=1);

namespace App\Bfs\Graph\HourRange;

class HourRangeModel
{
    public function __construct(
        private readonly string $stationCode,
        private readonly int $startHour,
        private readonly int $endHour
    )
    {

    }

    public function getStationCode(): string
    {
        return $this->stationCode;
    }

    public function getStartHour(): int
    {
        return $this->startHour;
    }

    public function getEndHour(): int
    {
        return $this->endHour;
    }
}
