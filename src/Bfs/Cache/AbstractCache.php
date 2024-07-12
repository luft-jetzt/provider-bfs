<?php declare(strict_types=1);

namespace App\Bfs\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

abstract class AbstractCache implements CacheInterface
{
    protected readonly FilesystemAdapter $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter(
            self::CACHE_NAMESPACE,
            self::CACHE_TTL,
            self::CACHE_DIRECTORY
        );
    }
}