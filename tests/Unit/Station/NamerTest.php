<?php

declare(strict_types=1);

namespace App\Tests\Unit\Station;

use App\Bfs\Station\Namer;
use App\Bfs\Website\StationModel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class NamerTest extends TestCase
{
    #[DataProvider('operatorProvider')]
    public function testGenerate(string $operator, string $expectedPrefix): void
    {
        $model = new StationModel();
        $model
            ->setOperator($operator)
            ->setLatitude(53.5)
            ->setLongitude(10.5)
        ;

        $code = Namer::generate($model);

        $this->assertStringStartsWith($expectedPrefix, $code);
        $this->assertNotEmpty($code);
    }

    public static function operatorProvider(): array
    {
        return [
            ['Bundesamt für Strahlenschutz (BfS)', 'BFS'],
            ['Umweltbundesamt', 'UBA'],
            ['Staatliches Gewerbeaufsichtsamt Hildesheim', 'GAAHI'],
            ['Bundesamt für Strahlenschutz', 'BFS'],
            ['Institut für Arbeitsschutz der DGUV (IFA)', 'IFA'],
            ['Leibniz-Institut für Troposphärenforschung e.V.', 'TROPOS'],
            ['Deutscher Wetterdienst', 'DWD'],
            ['Bayerisches Landesamt für Umwelt mit Bundesamt für Strahlenschutz', 'BFS'],
            ['Bundesanstalt für Arbeitsschutz und Arbeitsmedizin (BAuA)', 'BAUA'],
        ];
    }

    public function testUnknownOperatorReturnsEmptyString(): void
    {
        $model = new StationModel();
        $model
            ->setOperator('Unknown Operator')
            ->setLatitude(53.5)
            ->setLongitude(10.5)
        ;

        $this->assertSame('', Namer::generate($model));
    }

    public function testDifferentCoordinatesProduceDifferentCodes(): void
    {
        $model1 = new StationModel();
        $model1->setOperator('Bundesamt für Strahlenschutz (BfS)')->setLatitude(53.5)->setLongitude(10.5);

        $model2 = new StationModel();
        $model2->setOperator('Bundesamt für Strahlenschutz (BfS)')->setLatitude(48.1)->setLongitude(11.5);

        $this->assertNotEquals(Namer::generate($model1), Namer::generate($model2));
    }
}
