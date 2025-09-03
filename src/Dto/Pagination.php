<?php

namespace App\Dto;

final readonly class Pagination
{
    /**
     * @param list<int> $pages
     */
    public function __construct(
        public int $currentPage,
        public int $limit,
        public int $total,
        public int $totalPages,
        public int $offset,
        public int $firstItemIndex,
        public int $lastItemIndex,
        public bool $hasPrevious,
        public bool $hasNext,
        public ?int $previousPage,
        public ?int $nextPage,
        public array $pages
    ) {
    }
}
