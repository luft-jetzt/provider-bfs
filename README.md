# provider-bfs

CLI-only data provider for [luft.jetzt](https://luft.jetzt). It scrapes the
public UV-index daily-curve graphs published by the German Federal Office for
Radiation Protection (Bundesamt für Strahlenschutz, BfS), reads the current UV
index out of each graph image and pushes the values to the luft.jetzt API.

There is no HTTP interface; everything runs from `bin/console` as cron jobs.

## Requirements

- PHP 8.5 (extensions: `ctype`, `iconv`, `gd`)
- [Composer](https://getcomposer.org/)

## Setup

```bash
composer install
```

Configuration lives in `.env`; put secrets and environment-specific overrides
in an uncommitted `.env.local`.

## Commands

| Command | Description |
| --- | --- |
| `luft:station:cache` | Scrape the BfS station list and store it in the local file cache (`var/cache/`). |
| `luft:station:list-cache` | Print the currently cached stations. |
| `luft:station:load` | Push the cached stations to the luft.jetzt API. |
| `luft:fetch [station-code]` | Read the current UV index from each station graph and push the values. Pass one or more comma-separated station codes to restrict the run. |

## Typical operation

The station list changes rarely, the measured values change constantly, so the
two are refreshed on different schedules:

```bash
# Occasionally (e.g. daily): refresh the station cache and register stations.
bin/console luft:station:cache
bin/console luft:station:load

# Frequently (e.g. every few minutes during daylight): fetch and push values.
bin/console luft:fetch
```

`luft:fetch` requires a populated station cache; run `luft:station:cache`
first, otherwise it exits with an error.

## Tests and quality checks

```bash
vendor/bin/phpunit
vendor/bin/phpstan analyse
vendor/bin/php-cs-fixer fix --dry-run --diff
composer audit
```

These are the same checks the CI workflow (`.github/workflows/ci.yml`) runs on
every push and pull request.
