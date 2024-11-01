<?php

namespace App\Command;

use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'forecast:location-name',
    description: 'Get forecast for a given country code and location name',
)]
class ForecastLocationNameCommand extends Command
{
    public function __construct(
        private ForecastRepository $forecastRepository,
        private LocationRepository $locationRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('countryCode', InputArgument::REQUIRED, 'Country code of the location to check, e.g. "PL" or "SK"')
            ->addArgument('cityName', InputArgument::REQUIRED, 'Location name to check weather forecast for, e.g. "Warszawa" or "Bratislava"');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = $input->getArgument('countryCode');
        $cityName = $input->getArgument('cityName');
        $location = $this->locationRepository->findOneBy([
            'countryCode' => $countryCode,
            'name' => $cityName
        ]);
        if (!$location) {
            throw new \Exception("Location $countryCode/$cityName not found");
        }
        $forecastsArray = [];
        $forecasts = $this->forecastRepository->findForecastByLocation($location);
        foreach ($forecasts as $forecast) {
            $forecastsArray[] = [
                $forecast->getDate()->format('Y-m-d'),
                $forecast->getTemperatureCelsius(),
                $forecast->getFlTemperatureCelsius(),
                $forecast->getPressure(),
                $forecast->getHumidity(),
                $forecast->getWindSpeed(),
                $forecast->getWindDeg(),
                $forecast->getCloudiness(),
                $forecast->getIcon(),
            ];
        }

        $io->title("Forecast for $cityName, $countryCode");
        $io->horizontalTable([
            'Date',
            'Temperature',
            'Feels Like',
            'Pressure',
            'Humidity',
            'Wind Speed',
            'Wind Degree',
            'Cloudiness',
            'Icon'
        ], $forecastsArray);
        return Command::SUCCESS;
    }
}
