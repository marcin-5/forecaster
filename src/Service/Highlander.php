<?php
declare(strict_types=1);

namespace App\Service;

class Highlander
{
    private const MAX_RANDOM_NUMBER = 100;

    public function generateWeatherForecasts(int $threshold = 50, int $trials = 1): array
    {
        $forecasts = [];
        for ($i = 0; $i < $trials; $i++) {
            $forecasts[] = $this->getWeatherForecast($threshold);
        }

        return $forecasts;
    }

    private function getWeatherForecast(int $threshold): string
    {
        $draw = random_int(0, self::MAX_RANDOM_NUMBER);
        return $draw < $threshold ? "It's going to rain" : "It's going to be sunny";
    }
}
