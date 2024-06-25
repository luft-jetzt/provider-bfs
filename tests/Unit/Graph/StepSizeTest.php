<?php

namespace App\Tests\Unit\Graph;

use App\Bfs\Graph\MaxUvIndex;
use App\Bfs\Graph\StepSize;
use Imagine\Gd\Imagine;
use PHPUnit\Framework\TestCase;

class StepSizeTest extends TestCase
{


    /**
     * @dataProvider graphFilenameProvider
     */
    public function testStepSize(string $graphFilename, int $expectedStepSize): void
    {
        $imagine = new Imagine();
        $image = $imagine->open($graphFilename);

        $this->assertEquals($expectedStepSize, StepSize::detectStepSize($image));
    }

    public static function graphFilenameProvider(): array
    {
        return [
            [__DIR__ . '/../../graph/hamburg1.png', 21],
            [__DIR__ . '/../../graph/lueneburg1.png', 32],
            [__DIR__ . '/../../graph/lueneburg2.png', 21],
            [__DIR__ . '/../../graph/lueneburg3.png', 21],
        ];
    }
}
