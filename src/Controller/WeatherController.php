<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\HighlanderApiDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/weather')]
class WeatherController extends AbstractController
{
    #[Route('/highlander-says/api')]
    public function highlanderSaysApi(
        #[MapQueryString] ?HighlanderApiDTO $dto = null,
    ): Response
    {
        if (!$dto) {
            $dto = new HighlanderApiDTO();
            $dto->threshold = 5;
            $dto->trials = 1;
        }
        $forecasts = [];

        for ($i = 0; $i < $dto->trials; $i++) {
            $draw = random_int(0, 10);
            $forecast = $draw < $dto->threshold ? "It's going to rain" : "It's going to be sunny";
            $forecasts[] = $forecast;
        }
        $json = [
            'forecasts' => $forecasts,
            'threshold' => $dto->threshold,
        ];
        return new JsonResponse($json);
    }

    #[Route('/highlander-says/{threshold<\d+>}')]
    public function highlanderSays(
        Request      $request,
        RequestStack $requestStack,
        ?int         $threshold = null,
    ): Response
    {
        $session = $requestStack->getSession();
        if ($threshold) {
            $session->set('threshold', $threshold);
        } else {
            $threshold = $session->get('threshold', 5);
        }

        $trails = $request->get('trails', 1);
        $forecasts = [];

        for ($i = 0; $i < $trails; $i++) {
            $draw = random_int(0, 10);
            $forecast = $draw < $threshold ? "It's going to rain" : "It's going to be sunny";
            $forecasts[] = $forecast;
        }
        return $this->render('weather/highlander_says.html.twig', [
            'forecasts' => $forecasts,
            'threshold' => $threshold,
        ]);
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