<?php

namespace App\Repository;

use App\Entity\CitizenProjectCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CitizenProjectCategorySkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CitizenProjectCategory::class);
    }

    public function findByCitizenProjectCategory(CitizenProjectCategory $category): array
    {
        return $this
            ->createQueryBuilder('cpcs')
            ->leftJoin('cpcs.skill', 's')
            ->where('cpcs.category = :cpc')
            ->setParameter('cpc', $category)
            ->getQuery()
            ->getResult()
        ;
    }
}
