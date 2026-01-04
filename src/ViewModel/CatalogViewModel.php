<?php

namespace App\ViewModel;

use App\Dto\Pagination;
use App\Entity\Product;
use App\Entity\PharmacologicalGroup;
use App\Entity\AnimalSpecies;

/**
 *
 * @psalm-immutable
 */
final readonly class CatalogViewModel
{
    /**
     * @param array<int, Product> $products
     * @param array<int, PharmacologicalGroup> $groups
     * @param array<int, AnimalSpecies> $species
     */
    public function __construct(
        public array $products,
        public ?string $search,
        public array $groups,
        public array $species,
        public ?int $selectedGroup,
        public ?int $selectedSpecies,
        public Pagination $pagination
    ) {
    }
}
