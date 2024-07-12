<?php declare(strict_types=1);

namespace App\Tests\Unit\Graph;

use App\Bfs\Graph\HourRange\HourRangeModel;
use PHPUnit\Framework\TestCase;

class HourRangeModelTest extends TestCase
{
    public function testStartHour()
    {
        $hourRangeModel = new HourRangeModel('TEST123', 6, 21);

        $this->assertEquals(6, $hourRangeModel->getStartHour());
    }

    public function testEndHour()
    {
        $hourRangeModel = new HourRangeModel('TEST123', 6, 21);

        $this->assertEquals(21, $hourRangeModel->getEndHour());
    }
}
