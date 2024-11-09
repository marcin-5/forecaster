<?php

namespace App\Entity;

use App\Repository\ForecastRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ForecastRepository::class)]
class Forecast
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['api'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1)]
    #[Groups(['api'])]
    private ?string $temperatureCelsius = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1)]
    #[Groups(['api'])]
    private ?string $flTemperatureCelsius = null;

    #[ORM\Column(nullable: true)]
    private ?int $pressure = null;

    #[ORM\Column(nullable: true)]
    private ?int $humidity = null;

    #[ORM\Column(nullable: true)]
    private ?float $windSpeed = null;

    #[ORM\Column(nullable: true)]
    private ?int $windDeg = null;

    #[ORM\Column(nullable: true)]
    private ?int $cloudiness = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api'])]
    private ?string $icon = null;

    #[ORM\ManyToOne(inversedBy: 'forecasts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTemperatureFahrenheit(): ?string
    {
        return round(($this->getTemperatureCelsius() * 9 / 5) + 32, 1);
    }

    public function getTemperatureCelsius(): ?string
    {
        return $this->temperatureCelsius;
    }

    public function setTemperatureCelsius(string $temperatureCelsius): static
    {
        $this->temperatureCelsius = $temperatureCelsius;

        return $this;
    }

    public function getFlTemperatureFahrenheit(): ?string
    {
        return round(($this->getFlTemperatureCelsius() * 9 / 5) + 32, 1);
    }

    public function getFlTemperatureCelsius(): ?string
    {
        return $this->flTemperatureCelsius;
    }

    public function setFlTemperatureCelsius(string $flTemperatureCelsius): static
    {
        $this->flTemperatureCelsius = $flTemperatureCelsius;

        return $this;
    }

    public function getPressure(): ?int
    {
        return $this->pressure;
    }

    public function setPressure(?int $pressure): static
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getHumidity(): ?int
    {
        return $this->humidity;
    }

    public function setHumidity(?int $humidity): static
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getWindSpeed(): ?float
    {
        return $this->windSpeed;
    }

    public function setWindSpeed(?float $windSpeed): static
    {
        $this->windSpeed = $windSpeed;

        return $this;
    }

    public function getWindDeg(): ?int
    {
        return $this->windDeg;
    }

    public function setWindDeg(?int $windDeg): static
    {
        $this->windDeg = $windDeg;

        return $this;
    }

    public function getCloudiness(): ?int
    {
        return $this->cloudiness;
    }

    public function setCloudiness(?int $cloudiness): static
    {
        $this->cloudiness = $cloudiness;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }
}
