<?php declare(strict_types=1);

namespace App\Tests\Unit\Graph;

use App\Bfs\Graph\CurrentDateTime;
use Imagine\Gd\Imagine;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CurrentDateTimeTest extends TestCase
{
    #[DataProvider('graphFilenameProvider')]
    public function testCurrentDateTime(string $graphFilename, \DateTime $DateTime): void
    {
        $imagine = new Imagine();
        $image = $imagine->open($graphFilename);

        $currentDateTime = CurrentDateTime::calculate($image);

        $this->assertEquals($DateTime, $currentDateTime);
    }

    public static function graphFilenameProvider(): array
    {
        return [
            [__DIR__ . '/../../graph/boesel.png', (new \DateTime())->setTime(9, 3)],
            [__DIR__ . '/../../graph/friedrichshafen1.png', (new \DateTime())->setTime(10, 0)],
            [__DIR__ . '/../../graph/friedrichshafen2.png', (new \DateTime())->setTime(20, 58)],
            [__DIR__ . '/../../graph/hamburg1.png', (new \DateTime())->setTime(17, 50)],
            [__DIR__ . '/../../graph/hamburg2.png', (new \DateTime())->setTime(9, 54)],
            [__DIR__ . '/../../graph/lueneburg1.png', (new \DateTime())->setTime(18, 10)],
            [__DIR__ . '/../../graph/lueneburg2.png', (new \DateTime())->setTime(14, 9)],
            [__DIR__ . '/../../graph/lueneburg3.png', (new \DateTime())->setTime(15, 9)],
            [__DIR__ . '/../../graph/lueneburg4.png', (new \DateTime())->setTime(15, 0)],
            [__DIR__ . '/../../graph/lueneburg5.png', (new \DateTime())->setTime(17, 58)],
            [__DIR__ . '/../../graph/lueneburg6.png', (new \DateTime())->setTime(11, 9)],
            [__DIR__ . '/../../graph/schneefernhaus1.png', (new \DateTime())->setTime(9, 50)],
            [__DIR__ . '/../../graph/schneefernhaus2.png', (new \DateTime())->setTime(15, 10)],
        ];
    }
}
