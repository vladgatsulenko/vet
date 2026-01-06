<?php

namespace App\ViewModel;

use App\Dto\Pagination;
use App\Entity\Product;
use App\Entity\PharmacologicalGroup;
use App\Entity\AnimalSpecies;
use App\Entity\Manufacturer;

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
     * @param int[] $selectedManufacturers
     * @param array<int, mixed> $manufacturers  // placeholder if you later want to pass full manufacturer entities
     */
    public function __construct(
        public array $products,
        public ?string $search,
        public array $groups,
        public array $species,
        public array $manufacturers,
        public ?int $selectedGroup,
        public ?int $selectedSpecies,
        public array $selectedManufacturers,
        public Pagination $pagination
    ) {
    }
}
