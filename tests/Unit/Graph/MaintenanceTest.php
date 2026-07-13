<?php

declare(strict_types=1);

namespace App\Tests\Unit\Graph;

use App\Bfs\Graph\Maintenance;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MaintenanceTest extends TestCase
{
    #[DataProvider('graphFilenameProvider')]
    public function testIsMaintenance(string $graphFilename, bool $expectedResult): void
    {
        $imagine = new Imagine();
        $image = $imagine->open($graphFilename);

        $this->assertEquals($expectedResult, Maintenance::isMaintenance($image));
    }

    public static function graphFilenameProvider(): array
    {
        return [
            [__DIR__.'/../../graph/sanktaugustin.png', true],
            [__DIR__.'/../../graph/hamburg3.png', true],
            [__DIR__.'/../../graph/lueneburg1.png', false],
            [__DIR__.'/../../graph/schneefernhaus2.png', false],
        ];
    }

    /**
     * A maintenance box is only present when a *single* point list has all four
     * of its edges black. Two black edges in the first list plus two in the
     * second must NOT be reported as maintenance (the counter has to reset per
     * list).
     */
    public function testBlackEdgesSplitAcrossBothListsIsNotMaintenance(): void
    {
        $palette = new RGB();
        $image = (new Imagine())->create(new Box(500, 200), $palette->color('#ffffff'));

        $black = $palette->color('#000000');

        // Two edges from the first point list ...
        $image->draw()->dot(new Point(247, 80), $black);
        $image->draw()->dot(new Point(417, 80), $black);
        // ... and two from the second point list: four black edges in total,
        // but never four within one list.
        $image->draw()->dot(new Point(247, 120), $black);
        $image->draw()->dot(new Point(417, 120), $black);

        $this->assertFalse(Maintenance::isMaintenance($image));
    }
}
