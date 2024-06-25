<?php declare(strict_types=1);

namespace App\Bfs\Station;

use App\Bfs\Coordinate\Coord;
use App\Bfs\Website\StationModel;

class Namer
{
    private const OPERATOR_MAPPING = [
        'Bundesamt für Strahlenschutz (BfS)' => 'BFS',
        'Umweltbundesamt' => 'UBA',
        'Staatliches Gewerbeaufsichtsamt Hildesheim' => 'GAAHI',
        'Bundesamt für Strahlenschutz' => 'BFS',
        'Bundesamt für Strahlenschutz (BfS) Amt für Umweltschutz der Landeshauptstadt Stuttgart' => 'BFS',
        'Institut für Arbeitsschutz der DGUV (IFA)' => 'IFA',
        'Leibniz-Institut für Troposphärenforschung e.V.' => 'TROPOS',
        'Deutscher Wetterdienst' => 'DWD',
        'Bayerisches Landesamt für Umwelt mit Bundesamt für Strahlenschutz' => 'BFS',
        'Bundesamt für Strahlenschutz (BfS) Deutscher Wetterdienst (DWD)' => 'BFS',
        'Bundesamt für Strahlenschutz (BfS) Deutsche Lebens-Rettungs-Gesellschaft (DLRG)' => 'BFS',
        'Bundesanstalt für Arbeitsschutz und Arbeitsmedizin (BAuA)' => 'BAUA',
        'Bundesamt für Strahlenschutz (BfS) Bundesministerium für Umwelt, Naturschutz, nukleare Sicherheit und Verbraucherschutz (BMUV)' => 'BFS',
    ];

    private function __construct()
    {

    }

    public static function generate(StationModel $model): string
    {
        $hash = md5(sprintf('%f%f', $model->getLatitude(), $model->getLongitude()));

        $hashNumber = hexdec(substr($hash, 0, 8));

        $uniqueNumber = $hashNumber % 1000;

        if (array_key_exists($model->getOperator(), self::OPERATOR_MAPPING)) {
            $prefix = self::OPERATOR_MAPPING[$model->getOperator()];

            return sprintf('%s%d', $prefix, $uniqueNumber);
        }

        return '';
    }
}