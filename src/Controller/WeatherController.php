<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\HighlanderApiDTO;
use App\Service\Highlander;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/{_locale}/weather', requirements: [
    '_locale' => 'en|de'
])]
class WeatherController extends AbstractController
{
    #[Route('/highlander-says/api')]
    public function highlanderSaysApi(
        Highlander                          $highlander,
        #[MapQueryString] ?HighlanderApiDTO $dto = null,
    ): Response
    {
        if (!$dto) {
            $dto = new HighlanderApiDTO();
            $dto->threshold = 50;
            $dto->trials = 1;
        }

        $forecasts = $highlander->generateWeatherForecasts($dto->threshold, $dto->trials);
        $json = [
            'forecasts' => $forecasts,
            'threshold' => $dto->threshold,
        ];
        return new JsonResponse($json);
    }

    #[Route('/highlander-says/{threshold<\d+>}')]
    public function highlanderSays(
        Request                      $request,
        RequestStack                 $requestStack,
        TranslatorInterface          $translator,
        Highlander                   $highlander,
        ?int                         $threshold = null,
        #[MapQueryParameter] ?string $_format = 'html',
    ): Response
    {
        $session = $requestStack->getSession();
        if ($threshold) {
            $session->set('threshold', $threshold);
            $this->addFlash(
                'info',
                $translator->trans('weather.highlander_says.success', [
                    '%threshold%' => $threshold,
                ])
            );
        } else {
            $threshold = $session->get('threshold', 50);
        }
        $trials = (int)$request->get('trials', 1);

        $forecasts = $highlander->generateWeatherForecasts($threshold, $trials);
        $html = $this->renderView("weather/highlander_says.{$_format}.twig", [
            'forecasts' => $forecasts,
            'threshold' => $threshold,
        ]);
        return new Response($html);
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