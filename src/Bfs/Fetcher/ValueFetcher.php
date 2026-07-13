<?php

declare(strict_types=1);

namespace App\Bfs\Fetcher;

use App\Bfs\Graph\CurrentDateTime;
use App\Bfs\Graph\GraphDimensions;
use App\Bfs\Graph\HourRange;
use App\Bfs\Graph\Maintenance;
use App\Bfs\Graph\Point;
use App\Bfs\Graph\StepSize;
use App\Bfs\Website\StationModel;
use Caldera\LuftModel\Model\Value;
use Imagine\Gd\Imagine;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ValueFetcher implements ValueFetcherInterface
{
    private const string ALLOWED_IMAGE_HOST = 'bfs.de';

    private ?\DateTime $now = null;

    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    public function setNow(\DateTime $now): void
    {
        $this->now = $now;
    }

    public function fromStation(StationModel $stationModel): ?Value
    {
        $binaryImagecontent = $this->loadImageContent($stationModel->getCurrentImageUrl());

        $imagine = new Imagine();
        $image = $imagine->load($binaryImagecontent);

        $dateTime = CurrentDateTime::calculate($image);

        if (!$dateTime) {
            return null;
        }

        $value = new Value();
        $value
            ->setStationCode($stationModel->getStationCode())
            ->setPollutant('UVIndex')
            ->setDateTime($dateTime)
            ->setValue(0)
        ;

        if (Maintenance::isMaintenance($image)) {
            return null;
        }

        $hourRange = HourRange::calculate($image);

        $now = $this->now ?? new \DateTime('now', new \DateTimeZone('Europe/Berlin'));

        if ((int) $now->format('H') < $hourRange->getStartHour() || (int) $now->format('H') >= $hourRange->getEndHour()) {
            return $value;
        }

        $stepSize = StepSize::detectStepSize($image);

        $currentPoint = Point::detectCurrentPoint($image);

        $y = GraphDimensions::GRAPH_HEIGHT - $currentPoint->getY() + GraphDimensions::Y_AXIS_OFFSET;

        $uvIndex = round((($y / $stepSize) + 1) / 2, 1);

        return $value->setValue($uvIndex);
    }

    protected function loadImageContent(string $url): string
    {
        // A URL with a scheme is treated as a remote resource. Only http(s) to
        // the trusted BfS host is allowed, and it is always fetched through the
        // HTTP client. This prevents SSRF and local file disclosure via
        // untrusted schemes (file://, phar://, data://, ...) or hosts scraped
        // from the BfS station page. parse_url() cannot be trusted for scheme
        // detection here (it returns false for e.g. "phar:///..."), so the
        // scheme is extracted directly.
        if (preg_match('#^([a-zA-Z][a-zA-Z0-9+.\-]*):#', $url, $matches)) {
            $scheme = strtolower($matches[1]);

            if ('http' !== $scheme && 'https' !== $scheme) {
                throw new \RuntimeException(sprintf('Refusing to load image from disallowed URL scheme "%s": %s', $scheme, $url));
            }

            $host = parse_url($url, PHP_URL_HOST);

            if (!is_string($host) || !self::isAllowedHost($host)) {
                throw new \RuntimeException(sprintf('Refusing to load image from untrusted host: %s', $url));
            }

            $response = $this->httpClient->request('GET', $url);

            return $response->getContent();
        }

        // No scheme: a local filesystem path (used by tests and fixtures).
        $content = file_get_contents($url);

        if (false === $content) {
            throw new \RuntimeException(sprintf('Could not read file: %s', $url));
        }

        return $content;
    }

    private static function isAllowedHost(string $host): bool
    {
        $host = strtolower($host);

        return self::ALLOWED_IMAGE_HOST === $host || str_ends_with($host, '.'.self::ALLOWED_IMAGE_HOST);
    }
}
