<?php

declare(strict_types=1);

namespace App\Tests\Unit\Website;

use App\Bfs\Website\PageLinkModel;
use PHPUnit\Framework\TestCase;

class PageLinkModelTest extends TestCase
{
    public function testGetters(): void
    {
        $model = new PageLinkModel('https://example.com/station', 'Hamburg Station');

        $this->assertSame('https://example.com/station', $model->getUrl());
        $this->assertSame('Hamburg Station', $model->getCaption());
    }
}
