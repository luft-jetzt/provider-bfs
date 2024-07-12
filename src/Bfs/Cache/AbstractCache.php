<?php declare(strict_types=1);

namespace App\Bfs\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

abstract class AbstractCache implements CacheInterface
{
    protected FilesystemAdapter $cache;

}
