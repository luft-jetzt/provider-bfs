<?php

declare(strict_types=1);

namespace App\Tests\Unit\Website;

use App\Bfs\Website\PageLinkModel;
use App\Bfs\Website\StationLinkExtractor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class StationLinkExtractorTest extends TestCase
{
    public function testParseStationLinks(): void
    {
        $html = <<<'HTML'
<html><body>
<div id="main"><div id="content">
<ul>
    <li><a href="DE/themen/opt/uv/uv-index/tagesverlaeufe/station1.html">Station Eins</a></li>
    <li><a href="DE/themen/opt/uv/uv-index/tagesverlaeufe/station2.html">Station Zwei</a></li>
</ul>
</div></div>
</body></html>
HTML;

        $httpClient = new MockHttpClient(new MockResponse($html));
        $extractor = new StationLinkExtractor($httpClient);

        $links = $extractor->parseStationLinks();

        $this->assertCount(2, $links);
        $this->assertInstanceOf(PageLinkModel::class, $links[0]);
        $this->assertSame('https://www.bfs.de/DE/themen/opt/uv/uv-index/tagesverlaeufe/station1.html', $links[0]->getUrl());
        $this->assertSame('Station Eins', $links[0]->getCaption());
        $this->assertSame('https://www.bfs.de/DE/themen/opt/uv/uv-index/tagesverlaeufe/station2.html', $links[1]->getUrl());
    }

    public function testSessionIdIsRemovedFromUrls(): void
    {
        $html = <<<'HTML'
<html><body>
<div id="main"><div id="content">
<ul>
    <li><a href="DE/station.html;jsessionid=ABC123?param=1">Station</a></li>
</ul>
</div></div>
</body></html>
HTML;

        $httpClient = new MockHttpClient(new MockResponse($html));
        $extractor = new StationLinkExtractor($httpClient);

        $links = $extractor->parseStationLinks();

        $this->assertCount(1, $links);
        $this->assertSame('https://www.bfs.de/DE/station.html', $links[0]->getUrl());
    }

    public function testAbsoluteUrlsAreNotPrefixed(): void
    {
        $html = <<<'HTML'
<html><body>
<div id="main"><div id="content">
<ul>
    <li><a href="https://example.com/station.html">External</a></li>
</ul>
</div></div>
</body></html>
HTML;

        $httpClient = new MockHttpClient(new MockResponse($html));
        $extractor = new StationLinkExtractor($httpClient);

        $links = $extractor->parseStationLinks();

        $this->assertSame('https://example.com/station.html', $links[0]->getUrl());
    }

    public function testEmptyPageReturnsEmptyArray(): void
    {
        $html = '<html><body><div id="main"><div id="content"></div></div></body></html>';

        $httpClient = new MockHttpClient(new MockResponse($html));
        $extractor = new StationLinkExtractor($httpClient);

        $links = $extractor->parseStationLinks();

        $this->assertCount(0, $links);
    }
}
