<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/location-dummy')]
class LocationController extends AbstractController
{
    #[Route('/create')]
    public function create(EntityManagerInterface $entityManager): JsonResponse
    {
        $location = new Location();
        $location
            ->setName('Kielce')
            ->setCountryCode('PL')
            ->setLatitude(50.8660773)
            ->setLongitude(20.6285677);

        $entityManager->persist($location);

        $entityManager->flush();

        return new JsonResponse([
            'id' => $location->getId(),
        ]);
    }

    #[Route('/edit')]
    public function edit(
        LocationRepository     $locationRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse
    {
        $location = $locationRepository->find(6);
        $location->setName('KIELCE');

        $entityManager->flush();

        return new JsonResponse([
            'id' => $location->getId(),
            'name' => $location->getName(),
        ]);
    }
}
