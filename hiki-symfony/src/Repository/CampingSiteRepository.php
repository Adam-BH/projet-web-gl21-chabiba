<?php

namespace App\Repository;

use App\Entity\CampingSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CampingSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CampingSite::class);
    }

    /** @return CampingSite[] */
    public function findByFilters(?string $city = null, ?int $capacityMin = null): array
    {
        $qb = $this->createQueryBuilder('s');
        if ($city) {
            $qb->andWhere('s.city LIKE :city')->setParameter('city', '%'.$city.'%');
        }
        if ($capacityMin !== null) {
            $qb->andWhere('s.capacity >= :cap')->setParameter('cap', $capacityMin);
        }
        return $qb->getQuery()->getResult();
    }
}
