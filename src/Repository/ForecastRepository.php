<?php

namespace App\Repository;

use App\Entity\Forecast;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Forecast>
 */
class ForecastRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forecast::class);
    }


    /**
     * Finds the forecast data for a given location.
     *
     * @param Location $location The location entity for which to fetch the forecast.
     * @return array The found forecast data for the given location.
     */
    public function findForecastByLocation(Location $location): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.location = :location')
            ->setParameter('location', $location)
            ->andWhere('f.date >= CURRENT_DATE()')
            ->orderBy('f.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
