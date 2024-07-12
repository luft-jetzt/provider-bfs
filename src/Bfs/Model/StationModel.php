<?php declare(strict_types=1);

namespace App\Bfs\Model;

use Caldera\LuftModel\Model\Station as CalderaLuftStationModel;

class StationModel extends CalderaLuftStationModel
{
    private string $bfsPageUrl;
    private string $currentImageUrl;
    private string $operator;
    private string $location;

    public function setBfsPageUrl(string $bfsPageUrl): self
    {
        $this->bfsPageUrl = $bfsPageUrl;

        return $this;
    }

    public function getBfsPageUrl(): string
    {
        return $this->bfsPageUrl;
    }

    public function setCurrentImageUrl(string $currentImageUrl): self
    {
        $this->currentImageUrl = $currentImageUrl;

        return $this;
    }

    public function getCurrentImageUrl(): string
    {
        return $this->currentImageUrl;
    }

    public function setOperator(string $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }
}
