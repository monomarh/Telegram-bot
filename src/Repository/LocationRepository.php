<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    /**
     * @param string $city
     *
     * @return Location Returns an array of Location objects
     *
     * @throws NonUniqueResultException
     */
    public function findByCity(string $city): ?Location
    {
        $qb = $this->createQueryBuilder('location');

        return $qb->add('where', $qb->expr()->eq('location.city', ':city'))
            ->setParameter('city', $city)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $country
     *
     * @return Location Returns an array of Location objects
     *
     * @throws NonUniqueResultException
     */
    public function findByCountry(string $country): ?Location
    {
        $qb = $this->createQueryBuilder('location');

        return $qb->add('where', $qb->expr()->eq('location.country', ':country'))
            ->setParameter('country', $country)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
