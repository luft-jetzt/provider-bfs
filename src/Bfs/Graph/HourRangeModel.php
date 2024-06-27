<?php declare(strict_types=1);

namespace App\Bfs\Graph;

class HourRangeModel
{
    public function __construct(private readonly int $startHour, private readonly int $endHour)
    {

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
