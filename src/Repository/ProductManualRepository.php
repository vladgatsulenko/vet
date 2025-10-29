<?php

namespace App\Repository;

use App\Entity\ProductManual;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductManual>
 *
 * @method ProductManual|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductManual|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductManual[]    findAll()
 * @method ProductManual[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductManualRepository extends ServiceEntityRepository
{
    public function findOneByProduct(\App\Entity\Product $product): ?\App\Entity\ProductManual
    {
        return $this->findOneBy(['product' => $product]);
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductManual::class);
    }
}
