<?php

declare(strict_types=1);

namespace App\Tests\Unit\Website;

use App\Bfs\Website\StationModel;
use PHPUnit\Framework\TestCase;

class StationModelTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $model = new StationModel();
        $model
            ->setBfsPageUrl('https://www.bfs.de/station/test')
            ->setCurrentImageUrl('https://www.bfs.de/image/test.png')
            ->setOperator('Bundesamt für Strahlenschutz')
            ->setLocation('Hamburg')
        ;

        $this->assertSame('https://www.bfs.de/station/test', $model->getBfsPageUrl());
        $this->assertSame('https://www.bfs.de/image/test.png', $model->getCurrentImageUrl());
        $this->assertSame('Bundesamt für Strahlenschutz', $model->getOperator());
        $this->assertSame('Hamburg', $model->getLocation());
    }

    public function testFluentInterface(): void
    {
        $model = new StationModel();

        $this->assertSame($model, $model->setBfsPageUrl('url'));
        $this->assertSame($model, $model->setCurrentImageUrl('url'));
        $this->assertSame($model, $model->setOperator('op'));
        $this->assertSame($model, $model->setLocation('loc'));
    }
}
