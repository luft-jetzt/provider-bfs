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
            [__DIR__ . '/../../graph/boesel.png', self::createDateTime()->setTime(9, 3)],
            [__DIR__ . '/../../graph/friedrichshafen1.png', self::createDateTime()->setTime(10, 0)],
            [__DIR__ . '/../../graph/friedrichshafen2.png', self::createDateTime()->setTime(20, 58)],
            [__DIR__ . '/../../graph/hamburg1.png', self::createDateTime()->setTime(17, 50)],
            [__DIR__ . '/../../graph/hamburg2.png', self::createDateTime()->setTime(9, 54)],
            [__DIR__ . '/../../graph/lueneburg1.png', self::createDateTime()->setTime(18, 10)],
            [__DIR__ . '/../../graph/lueneburg2.png', self::createDateTime()->setTime(14, 9)],
            [__DIR__ . '/../../graph/lueneburg3.png', self::createDateTime()->setTime(15, 9)],
            [__DIR__ . '/../../graph/lueneburg4.png', self::createDateTime()->setTime(15, 0)],
            [__DIR__ . '/../../graph/lueneburg5.png', self::createDateTime()->setTime(17, 58)],
            [__DIR__ . '/../../graph/lueneburg6.png', self::createDateTime()->setTime(11, 9)],
            [__DIR__ . '/../../graph/schneefernhaus1.png', self::createDateTime()->setTime(9, 50)],
            [__DIR__ . '/../../graph/schneefernhaus2.png', self::createDateTime()->setTime(15, 10)],
        ];
    }

    public function testEmptyDataDateTimeFailure(): void
    {
        $imagine = new Imagine();
        $image = $imagine->open(__DIR__ . '/../../graph/melpitz.png');

        $currentDateTime = CurrentDateTime::calculate($image);

        $this->assertNull($currentDateTime);
    }

    protected static function createDateTime(): \DateTime
    {
        return (new \DateTime())->setTimezone(new \DateTimeZone('Europe/Berlin'));
    }
}
