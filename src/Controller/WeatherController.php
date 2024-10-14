<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/weather')]
class WeatherController extends AbstractController
{
    #[Route('/{countryCode}/{city}')]
    public function forecast(string $countryCode, string $city): Response
    {
        $content = "<html><body>Forecast for $countryCode: $city</body></html>";
        return new Response($content);
    }
}
