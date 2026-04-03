<?php

declare(strict_types=1);

namespace App\Tests\Unit\Graph;

use App\Bfs\Graph\CurrentDateTime;
use Imagine\Gd\Imagine;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CurrentDateTimeTest extends TestCase
{
    #[DataProvider('graphFilenameProvider')]
    public function testCurrentDateTime(string $graphFilename, string $expectedTime): void
    {
        $imagine = new Imagine();
        $image = $imagine->open($graphFilename);

        $currentDateTime = CurrentDateTime::calculate($image);

        $this->assertNotNull($currentDateTime);
        $this->assertEquals($expectedTime, $currentDateTime->format('H:i'));
    }

    public static function graphFilenameProvider(): array
    {
        return [
            [__DIR__.'/../../graph/boesel.png', '09:03'],
            [__DIR__.'/../../graph/friedrichshafen1.png', '10:00'],
            [__DIR__.'/../../graph/friedrichshafen2.png', '20:58'],
            [__DIR__.'/../../graph/hamburg1.png', '17:50'],
            [__DIR__.'/../../graph/hamburg2.png', '09:54'],
            [__DIR__.'/../../graph/lueneburg1.png', '18:10'],
            [__DIR__.'/../../graph/lueneburg2.png', '14:09'],
            [__DIR__.'/../../graph/lueneburg3.png', '15:09'],
            [__DIR__.'/../../graph/lueneburg4.png', '15:00'],
            [__DIR__.'/../../graph/lueneburg5.png', '17:58'],
            [__DIR__.'/../../graph/lueneburg6.png', '11:09'],
            [__DIR__.'/../../graph/schneefernhaus1.png', '09:50'],
            [__DIR__.'/../../graph/schneefernhaus2.png', '15:10'],
        ];
    }

    public function testEmptyDataDateTimeFailure(): void
    {
        $imagine = new Imagine();
        $image = $imagine->open(__DIR__.'/../../graph/melpitz.png');

        $currentDateTime = CurrentDateTime::calculate($image);

        $this->assertNull($currentDateTime);
    }
}
