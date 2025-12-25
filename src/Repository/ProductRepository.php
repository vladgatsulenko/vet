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

    public function search(?string $search): array
    {
        $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.pharmacologicalGroup', 'g')
        ->leftJoin('p.animalSpecies', 's')
        ->addSelect('g', 's');


        $search = $search !== null ? trim($search) : null;
        if ($search === '') {
            $search = null;
        }

        if ($search !== null) {
            $term = '%' . mb_strtolower($search) . '%';

            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('LOWER(p.name)', ':term'),
                    $qb->expr()->like('LOWER(p.descriptionShort)', ':term'),
                    $qb->expr()->like('LOWER(p.descriptionMedium)', ':term')
                )
            )
            ->setParameter('term', $term);
        }

        return $qb->orderBy('p.name', 'ASC')->getQuery()->getResult();
    }
}
