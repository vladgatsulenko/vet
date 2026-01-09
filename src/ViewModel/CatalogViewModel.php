<?php

namespace App\ViewModel;

use App\Dto\Pagination;
use App\Entity\Product;
use App\Entity\PharmacologicalGroup;
use App\Entity\AnimalSpecies;
use App\Entity\Manufacturer;

/**
 * @psalm-immutable
 *
 * @param Product[] $products
 * @param PharmacologicalGroup[] $groups
 * @param AnimalSpecies[] $species
 * @param Manufacturer[] $manufacturers
 * @param int[] $selectedManufacturers
 */
final readonly class CatalogViewModel
{
    /**
     * @param Product[] $products
     * @param PharmacologicalGroup[] $groups
     * @param AnimalSpecies[] $species
     * @param Manufacturer[] $manufacturers
     * @param int[] $selectedManufacturers
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

