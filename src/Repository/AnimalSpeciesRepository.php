<?php

namespace App\Repository;

use App\Entity\AnimalSpecies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnimalSpecies>
 *
 * @method AnimalSpecies|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnimalSpecies|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnimalSpecies[]    findAll()
 * @method AnimalSpecies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalSpeciesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimalSpecies::class);
    }

    /**
     *
     * @return AnimalSpecies[]
     */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
