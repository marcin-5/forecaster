<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/location-dummy')]
class LocationController extends AbstractController
{
    #[Route('/create')]
    public function create(LocationRepository $locationRepository): JsonResponse
    {
        $location = new Location();
        $location
            ->setName('Kielce')
            ->setCountryCode('PL')
            ->setLatitude(50.8660773)
            ->setLongitude(20.6285677);

        $locationRepository->save($location, true);

        return new JsonResponse([
            'id' => $location->getId(),
        ]);
    }

    #[Route('/edit')]
    public function edit(
        LocationRepository $locationRepository,
    ): JsonResponse
    {
        $location = $locationRepository->find(7);
        $location->setName('KIELCE');

        $locationRepository->save($location, true);

        return new JsonResponse([
            'id' => $location->getId(),
            'name' => $location->getName(),
        ]);
    }

    #[Route('/remove/{id}')]
    public function remove(
        LocationRepository $locationRepository,
        int                $id,
    ): JsonResponse
    {
        $location = $locationRepository->find($id);
        $locationRepository->remove($location, flush: true);

        return new JsonResponse(null);
    }

    #[Route('/show/{name}')]
    public function show(
        LocationRepository $locationRepository,
        string             $name,
    ): JsonResponse
    {
        $location = $locationRepository->findOneByName($name);

        $json = [
            'id' => $location->getId(),
            'name' => $location->getName(),
            'country' => $location->getCountryCode(),
            'lat' => $location->getLatitude(),
            'long' => $location->getLongitude(),
        ];

        return new JsonResponse($json);
    }

    #[Route('/')]
    public function index(
        LocationRepository $locationRepository,
    ): JsonResponse
    {
        $locations = $locationRepository->findAll();

        $json = [];
        foreach ($locations as $location) {
            $json[] = [
                'id' => $location->getId(),
                'name' => $location->getName(),
                'country' => $location->getCountryCode(),
                'lat' => $location->getLatitude(),
                'long' => $location->getLongitude(),
            ];
        }

        return new JsonResponse($json);
    }
}
