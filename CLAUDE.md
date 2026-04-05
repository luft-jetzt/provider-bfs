# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

A Symfony console application that fetches UV index data from the Swiss Federal Office of Meteorology and Climatology (BFS). Extracts UV values by analyzing PNG graph images pixel-by-pixel using the Imagine library, then pushes data to the Luft.jetzt API.

- **PHP**: ^8.5
- **Framework**: Symfony 8.0
- **Data Source**: BFS website (PNG graph images)
- **Pollutant**: UV Index

## Common Commands

```bash
composer install                           # Install dependencies
php bin/console luft:fetch                 # Fetch current UV values and push to API
php bin/console luft:fetch STATIONCODE     # Fetch for specific station only
php bin/console luft:station:load          # Load stations from BFS website
php bin/console luft:station-cache:save    # Save station cache to filesystem
php bin/console luft:station-cache:list    # List cached stations

vendor/bin/phpunit                         # Run tests
vendor/bin/phpstan analyse --no-progress   # Static analysis
vendor/bin/php-cs-fixer fix               # Code style fixing
```

## Architecture

### Data Extraction Pipeline

1. **`StationLinkExtractor`** — Scrapes BFS website for station listing pages
2. **`StationPageParser`** — Parses individual station pages for metadata and graph image URLs
3. **`ValueFetcher`** — Downloads PNG graph images and extracts UV values via pixel analysis
4. Graph analysis classes (`GraphDimensions`, `Point`, `StepSize`, `HourRange`, `CurrentDateTime`) — Pixel-by-pixel image parsing to read UV values from graph images

### Key Classes

- **`src/Bfs/Fetcher/ValueFetcher.php`** — Core: loads images (HTTP or local), extracts UV values via Imagine
- **`src/Bfs/StationModel.php`** — Extended Station model with BFS-specific properties (page URL, image URL, operator)
- **`src/Bfs/Coordinate.php`** — Coordinate conversion utilities
- **`src/Bfs/Maintenance.php`** — Handles BFS maintenance/downtime detection
- **`src/Command/Luft/FetchCommand.php`** — Main fetch command

### Station Cache

Uses Symfony cache adapter for caching station metadata between runs. Commands for saving, listing, and managing station cache.

## Dependencies

- `imagine/imagine` ^1.5 — Image processing for graph analysis
- `symfony/http-client` ^8.0 — HTTP requests to BFS website
- `symfony/cache` ^8.0 — Station caching
- `luft-jetzt/luft-api-bundle` ^1.0 — Pushes data to Luft.jetzt API
