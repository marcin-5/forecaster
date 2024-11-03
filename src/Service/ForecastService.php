<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\LocationNotFoundException;
use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;

class ForecastService
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly ForecastRepository $forecastRepository,
    )
    {
    }

    public function getForecastsForLocationName(
        string $countryCode,
        string $locationName,
    ): array
    {
        $location = $this->locationRepository->findOneBy([
            'countryCode' => $countryCode,
            'name' => $locationName,
        ]);
        if (!$location) {
            throw new LocationNotFoundException();
        }

        $forecasts = $this->forecastRepository->findForecastByLocation($location);

        return [$location, $forecasts];
    }
}
