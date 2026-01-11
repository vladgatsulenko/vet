<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\PharmacologicalGroup;
use App\Entity\AnimalSpecies;
use App\Entity\Manufacturer;
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
     * Build search QueryBuilder.
     *
     * @param string|null $search
     * @param PharmacologicalGroup|null $group
     * @param AnimalSpecies|null $species
     * @param Manufacturer[]|int[]|null $manufacturers array of Manufacturer entities OR array of ids (int)
     * @return QueryBuilder
     */
    public function createSearchQueryBuilder(
        ?string $search,
        ?PharmacologicalGroup $group = null,
        ?AnimalSpecies $species = null,
        ?array $manufacturers = null
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.pharmacologicalGroup', 'g')
            ->leftJoin('p.animalSpecies', 's')
            ->leftJoin('p.manufacturer', 'm')
            ->addSelect('g', 's', 'm');

        if ($group !== null) {
            $qb->andWhere('g = :group')->setParameter('group', $group);
        }

        if ($species !== null) {
            $qb->andWhere('s = :species')->setParameter('species', $species);
        }

        if (!empty($manufacturers)) {
            $first = reset($manufacturers);
            if ($first instanceof Manufacturer) {
                $qb->andWhere('m IN (:manufacturers)')->setParameter('manufacturers', $manufacturers);
            } else {
                $ids = array_values(array_map('intval', $manufacturers));
                $qb->andWhere('m.id IN (:manufacturers)')->setParameter('manufacturers', $ids);
            }
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
     * Shortcut for full-text-ish search (keeps BC).
     *
     * @param string|null $search
     * @return Product[]
     */
    public function search(?string $search): array
    {
        return $this->createSearchQueryBuilder($search)->getQuery()->getResult();
    }

    /**
     * Count matching products.
     *
     * @param string|null $search
     * @param PharmacologicalGroup|null $group
     * @param AnimalSpecies|null $species
     * @param Manufacturer[]|int[]|null $manufacturers
     * @return int
     */
    public function countBySearch(
        ?string $search,
        ?PharmacologicalGroup $group = null,
        ?AnimalSpecies $species = null,
        ?array $manufacturers = null
    ): int {
        $qb = $this->createSearchQueryBuilder($search, $group, $species, $manufacturers);

        $qbCount = clone $qb;
        $qbCount->select('COUNT(DISTINCT p.id)');

        return (int) $qbCount->getQuery()->getSingleScalarResult();
    }

    /**
     * Suggestions for autocomplete.
     *
     * @param string $term
     * @param int $limit
     * @return Product[]
     */
    public function findSuggestions(string $term, int $limit = 10): array
    {
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
     * Paginated search results.
     *
     * @param int $offset
     * @param int $limit
     * @param string|null $search
     * @param PharmacologicalGroup|null $group
     * @param AnimalSpecies|null $species
     * @param Manufacturer[]|int[]|null $manufacturers
     * @return Product[]
     */
    public function findPaginatedBySearch(
        int $offset,
        int $limit,
        ?string $search = null,
        ?PharmacologicalGroup $group = null,
        ?AnimalSpecies $species = null,
        ?array $manufacturers = null
    ): array {
        $qb = $this->createSearchQueryBuilder($search, $group, $species, $manufacturers);
        $qb->setFirstResult($offset)
           ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
