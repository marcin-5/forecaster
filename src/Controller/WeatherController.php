<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/weather')]
class WeatherController extends AbstractController
{
    #[Route('/highlander-says/api')]
    public function highlanderSaysApi(#[MapQueryParameter] int $threshold = 5): Response
    {
        $draw = random_int(0, 10);
        $json = ['forecast' => $draw < $threshold ? "It's going to rain" : "It's going to be sunny",
            'threshold' => $threshold,
            'self' => $this->generateUrl('app_weather_highlandersaysapi',
                ['threshold' => $threshold],
                UrlGeneratorInterface::ABSOLUTE_URL
            )];
        return new JsonResponse($json);
    }

    #[Route('/highlander-says/{threshold<\d+>?5}')]
    public function highlanderSays(int $threshold, Request $request): Response
    {
        $trails = $request->get('trails', 1);
        $forecasts = [];

        for ($i = 0; $i < $trails; $i++) {
            $draw = random_int(0, 10);
            $forecast = $draw < $threshold ? "It's going to rain" : "It's going to be sunny";
            $forecasts[] = $forecast;
        }
        return $this->render('weather/highlander_says.html.twig', ['forecasts' => $forecasts]);
    }

    #[Route('/highlander-says/{guess}')]
    public function highlanderSaysGuess(string $guess): Response
    {
        $available = ['rain', 'snow', 'hail'];
        if (!in_array($guess, $available)) {
            throw $this->createNotFoundException('The guess is not found.');
        }
        $forecast = "It's going to $guess";
        return $this->render('weather/highlander_says.html.twig', ['forecasts' => [$forecast]]);
    }
}