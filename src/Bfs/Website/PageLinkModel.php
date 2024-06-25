<?php declare(strict_types=1);

namespace App\Bfs\Website;

class PageLinkModel
{
    public function __construct(private readonly string $url, private readonly string $caption)
    {

    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCaption(): string
    {
        return $this->caption;
    }
}
