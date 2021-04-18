<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    /**
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
