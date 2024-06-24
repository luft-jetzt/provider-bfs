<?php declare(strict_types=1);

namespace App\Bfs\Website;

class StationModel
{
    private string $stationCode;
    private string $operator;
    private string $location;
    private int $altitude;
    private float $latitude;
    private float $longitude;

    public function getStationCode(): string
    {
        return $this->stationCode;
    }

    public function setStationCode(string $stationCode): self
    {
        $this->stationCode = $stationCode;

        return $this;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function setOperator(string $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getAltitude(): int
    {
        return $this->altitude;
    }

    public function setAltitude(int $altitude): self
    {
        $this->altitude = $altitude;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}