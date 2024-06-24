<?php declare(strict_types=1);

namespace App\Bfs\Station;

interface LoaderInterface
{
    public function load(): array;
}