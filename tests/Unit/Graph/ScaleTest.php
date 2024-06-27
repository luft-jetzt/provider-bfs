<?php declare(strict_types=1);

namespace App\Tests\Unit\Graph;

use App\Bfs\Graph\Scale;
use Imagine\Gd\Imagine;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ScaleTest extends TestCase
{
    #[DataProvider('graphFilenameProvider')]
    public function testSizeY(string $graphFilename, int $expectedYSize, int $expecteXSize): void
    {
        $imagine = new Imagine();
        $image = $imagine->open($graphFilename);

        $this->assertEquals($expectedYSize, Scale::sizeY($image));
    }

    #[DataProvider('graphFilenameProvider')]
    public function testSizeX(string $graphFilename, int $expectedYSize, int $expectedXSize): void
    {
        $imagine = new Imagine();
        $image = $imagine->open($graphFilename);

        $this->assertEquals($expectedXSize, Scale::sizeX($image));
    }

    public static function graphFilenameProvider(): array
    {
        return [
            [__DIR__ . '/../../graph/hamburg1.png', 20, 16],
            [__DIR__ . '/../../graph/lueneburg1.png', 14, 16],
            [__DIR__ . '/../../graph/schneefernhaus1.png', 24, 16],
            [__DIR__ . '/../../graph/schneefernhaus2.png', 24, 16],
        ];
    }
}
