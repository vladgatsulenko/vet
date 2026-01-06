<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

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
     *
     * @param string|null $search
     * @param int|null $groupId
     * @param int|null $speciesId
     * @return QueryBuilder
     */
    public function createSearchQueryBuilder(?string $search, ?int $groupId = null, ?int $speciesId = null, ?array $manufacturerIds = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.pharmacologicalGroup', 'g')
            ->leftJoin('p.animalSpecies', 's')
            ->leftJoin('p.manufacturer', 'm')
            ->addSelect('g', 's', 'm');

        if ($groupId !== null) {
            $qb->andWhere('g.id = :gid')->setParameter('gid', $groupId);
        }

        if ($speciesId !== null) {
            $qb->andWhere('s.id = :sid')->setParameter('sid', $speciesId);
        }

        if (!empty($manufacturerIds)) {
            $manufacturerIds = array_values(array_map('intval', $manufacturerIds));
            $qb->andWhere('m.id IN (:mids)')->setParameter('mids', $manufacturerIds);
        }

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

        return $qb->orderBy('p.name', 'ASC');
    }

    /**
     *
     * @param string|null $search
     * @return Product[]
     */
    public function search(?string $search): array
    {
        return $this->createSearchQueryBuilder($search)->getQuery()->getResult();
    }

    /**
     *
     * @param string|null $search
     * @param int|null $groupId
     * @param int|null $speciesId
     * @param int[]|null $manufacturerIds
     * @return int
     */
    public function countBySearch(?string $search, ?int $groupId = null, ?int $speciesId = null,  ?array $manufacturerIds = null): int
    {
        $qb = $this->createSearchQueryBuilder($search, $groupId, $speciesId, $manufacturerIds);

        $qbCount = clone $qb;
        $qbCount->select('COUNT(DISTINCT p.id)');

        return (int) $qbCount->getQuery()->getSingleScalarResult();
    }

     /**
     *
     * @param string $term
     * @param int $limit
     * @return Product[]
     */
    
    public function findSuggestions(string $term, int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->leftJoin('p.pharmacologicalGroup', 'g')
            ->leftJoin('p.animalSpecies', 's')
            ->addSelect('g','s')
            ->where($qbExpr = $this->createQueryBuilder('p')->expr()->like('LOWER(p.name)', ':term'))
        ;

        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.pharmacologicalGroup', 'g')
            ->leftJoin('p.animalSpecies', 's')
            ->addSelect('g', 's')
            ->andWhere('LOWER(p.name) LIKE :term')
            ->setParameter('term', '%' . mb_strtolower($term) . '%')
            ->orderBy('p.name', 'ASC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }


    /**
     *
     * @param int $offset
     * @param int $limit
     * @param string|null $search
     * @param int|null $groupId
     * @param int|null $speciesId
     * @param int[]|null $manufacturerIds
     * @return Product[]
     */
    public function findPaginatedBySearch(
        int $offset,
        int $limit,
        ?string $search = null,
        ?int $groupId = null,
        ?int $speciesId = null,
        ?array $manufacturerIds = null
    ): array {
        $qb = $this->createSearchQueryBuilder($search, $groupId, $speciesId, $manufacturerIds);
        $qb->setFirstResult($offset)
           ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
