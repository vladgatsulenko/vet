<?php

namespace App\Repository;

use App\Entity\PharmacologicalGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PharmacologicalGroup>
 *
 * @method PharmacologicalGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PharmacologicalGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PharmacologicalGroup[]    findAll()
 * @method PharmacologicalGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PharmacologicalGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PharmacologicalGroup::class);
    }

    /**
     *
     * @return PharmacologicalGroup[]
     */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
