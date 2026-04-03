<?php

declare(strict_types=1);

namespace App\Tests\Unit\Graph;

use App\Bfs\Graph\HourRangeModel;
use PHPUnit\Framework\TestCase;

class HourRangeModelTest extends TestCase
{
    public function testRange6To21(): void
    {
        $model = new HourRangeModel(6, 21);

        $this->assertEquals(6, $model->getStartHour());
        $this->assertEquals(21, $model->getEndHour());
    }

    public function testRange7To18(): void
    {
        $model = new HourRangeModel(7, 18);

        $this->assertEquals(7, $model->getStartHour());
        $this->assertEquals(18, $model->getEndHour());
    }
}
