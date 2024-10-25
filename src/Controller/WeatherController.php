<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class WeatherController extends AbstractController
{
    #[Route("/weather/{countryCode}/{city}")]
    public function forecast(
        LocationRepository $locationRepository,
        ForecastRepository $forecastRepository,
        string             $countryCode,
        string             $city
    ): Response
    {
        $location = $locationRepository->findOneBy([
            'countryCode' => $countryCode,
            'name' => $city
        ]);
        if (!$location) {
            throw $this->createNotFoundException("Location $countryCode/$city not found");
        }

        $forecasts = $forecastRepository->findForecastByLocation($location);
        return $this->render('weather/forecast.html.twig', [
            'forecasts' => $forecasts,
            'location' => $location,
        ]);
    }
}
