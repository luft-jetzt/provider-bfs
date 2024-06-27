<?php declare(strict_types=1);

namespace App\Tests\Unit\Graph;

use App\Bfs\Graph\MaxUvIndex;
use Imagine\Gd\Imagine;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MaxUvIndexTest extends TestCase
{
    #[DataProvider('graphFilenameProvider')]
    public function testMaxUvIndex(string $graphFilename, int $expectedMaxUvIndex): void
    {
        $imagine = new Imagine();
        $image = $imagine->open($graphFilename);

        $this->assertEquals($expectedMaxUvIndex, MaxUvIndex::detectMaxUvIndex($image));
    }

    public static function graphFilenameProvider(): array
    {
        return [
            [__DIR__ . '/../../graph/hamburg1.png', 9],
            [__DIR__ . '/../../graph/lueneburg1.png', 6],
        ];
    }
}
