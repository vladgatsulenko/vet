<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function findByFilters(?int $groupId, ?int $speciesId, ?string $q): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.pharmacologicalGroup', 'g')
            ->leftJoin('p.animalSpecies', 's')
            ->addSelect('g', 's');

        if ($groupId) {
            $qb->andWhere('g.id = :gid')->setParameter('gid', $groupId);
        }
        if ($speciesId) {
            $qb->andWhere('s.id = :sid')->setParameter('sid', $speciesId);
        }
        if ($q) {
            $qb->andWhere('p.name LIKE :q OR p.descriptionShort LIKE :q')
               ->setParameter('q', '%' . $q . '%');
        }

        return $qb->orderBy('p.name', 'ASC')->getQuery()->getResult();
    }
}
