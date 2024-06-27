<?php declare(strict_types=1);

namespace App\Tests\Unit\Graph;

use App\Bfs\Graph\Maintenance;
use Imagine\Gd\Imagine;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MaintenanceTest extends TestCase
{
    #[DataProvider('graphFilenameProvider')]
    public function testPoint(string $graphFilename, bool $expectedResult): void
    {
        $imagine = new Imagine();
        $image = $imagine->open($graphFilename);

        $this->assertEquals($expectedResult, Maintenance::isMaintenance($image));
    }

    public static function graphFilenameProvider(): array
    {
        return [
            [__DIR__ . '/../../graph/sanktaugustin.png', true],
            [__DIR__ . '/../../graph/lueneburg1.png', false],
            [__DIR__ . '/../../graph/schneefernhaus2.png', false],
        ];
    }
}
