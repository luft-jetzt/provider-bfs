<?php

declare(strict_types=1);

namespace App\Tests\Unit\Website;

use App\Bfs\Website\StationPageParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class StationPageParserTest extends TestCase
{
    public function testParse(): void
    {
        $html = <<<'HTML'
<html><body>
<div class="singleview"><h1>Messstation Hamburg</h1></div>
<div id="heute"><div class="picture linksOhne"><img src="https://www.bfs.de/graph/hamburg.png" /></div></div>
<table><tbody>
    <tr><td>Betreiber</td><td>Bundesamt für Strahlenschutz (BfS)</td></tr>
    <tr><td>Standort</td><td>Hamburg-Neustadt</td></tr>
    <tr><td>Höhe</td><td>25</td></tr>
    <tr><td>Koordinaten</td><td>53°33'10" Nord<br>
9°58'30" Ost</td></tr>
</tbody></table>
</body></html>
HTML;

        $httpClient = new MockHttpClient(new MockResponse($html));
        $parser = new StationPageParser($httpClient);

        $station = $parser->parse('https://www.bfs.de/DE/station/hamburg.html');

        $this->assertSame('https://www.bfs.de/DE/station/hamburg.html', $station->getBfsPageUrl());
        $this->assertSame('https://www.bfs.de/graph/hamburg.png', $station->getCurrentImageUrl());
        $this->assertSame('Messstation Hamburg', $station->getTitle());
        $this->assertSame('Bundesamt für Strahlenschutz (BfS)', $station->getOperator());
        $this->assertSame('Hamburg-Neustadt', $station->getLocation());
        $this->assertSame(25, $station->getAltitude());
        $this->assertEqualsWithDelta(53.5527778, $station->getLatitude(), 0.001);
        $this->assertEqualsWithDelta(9.975, $station->getLongitude(), 0.001);
    }
}
