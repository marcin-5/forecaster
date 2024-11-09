<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationApiType;
use App\Repository\ForecastRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1/weather')]
class WeatherApiController extends AbstractController
{
    #[Route('/json/{id}', name: 'app_weather_api')]
    public function jsonAction(Location $location): Response
    {
        $data = [
            'id' => $location->getId(),
            'name' => $location->getName(),
            'country' => $location->getCountryCode(),
        ];

        foreach ($location->getForecasts() as $forecast) {
            $data['forecasts'][$forecast->getDate()->format('Y-m-d')] = [
                'celsius' => $forecast->getCelsius(),
            ];
        }

//        return $this->json($data);
//        return new JsonResponse($data);
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/jsont/{id}')]
    public function jsonTwigAction(Location $location): Response
    {
        $content = $this->renderView('weather_api/json_twig.json.twig', [
            'location' => $location,
        ]);

        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/csvt/{id}')]
    public function csvTwigAction(Location $location): Response
    {
        $content = $this->renderView('weather_api/csv_twig.csv.twig', [
            'location' => $location,
        ]);

        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');

        return $response;
    }

    #[Route('/serializer/{id}')]
    public function serializerAction(
        Location            $location,
        SerializerInterface $serializer,
    ): Response
    {
//        // json
//        $content = $serializer->serialize($location, 'json');
//
//        $response = new Response($content);
//        $response->headers->set('Content-Type', 'application/json');
//
//        return $response;


//        // yaml
//        $content = $serializer->serialize($location, 'yaml', [YamlEncoder::YAML_INLINE => 1]);
//
//        $response = new Response($content);
//        $response->headers->set('Content-Type', 'text/yaml');
//
//        return $response;


//        // csv
//        $content = $serializer->serialize($location, 'csv');
//
//        $response = new Response($content);
//        $response->headers->set('Content-Type', 'text/csv');
//
//        return $response;


        return $this->json($location);
    }

    #[Route("/forecast/{id}/{date}", methods: "PATCH")]
    public function patchForecastsPayloadAction(
        Request                $request,
        ForecastRepository     $forecastRepository,
        EntityManagerInterface $entityManager,
        int                    $id,
        string                 $date,
    ): JsonResponse
    {
        $payload = $request->toArray();
        $celsius = $payload['celsius'];

        $forecast = $forecastRepository->findOneBy([
            'location' => $id,
            'date' => new \DateTime($date),
        ]);
        if (!$forecast) {
            throw $this->createNotFoundException();
        }

        $forecast->setCelsius($celsius);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/location')]
    public function postLocation(
        EntityManagerInterface        $em,
        #[MapRequestPayload] Location $location,
    ): JsonResponse
    {
        $em->persist($location);
        $em->flush();

        return $this->json([
            'success' => true,
            'location' => $location,
        ]);
    }

    #[Route('/location-form')]
    public function postLocationForm(
        Request                $request,
        EntityManagerInterface $em,
    ): JsonResponse
    {
        $location = new Location();
        $form = $this->createForm(LocationApiType::class, $location);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($location);
            $em->flush();

            return $this->json([
                'success' => true,
                'location' => $location,
            ]);
        } else {
            return $this->json([
                'success' => false,
                'errors' => (string)$form->getErrors()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
