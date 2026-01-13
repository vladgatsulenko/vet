<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class CatalogQuery
{
    public const DEFAULT_PAGE = 1;
    public const DEFAULT_LIMIT = 12;

    /**
     * @param int[] $manufacturers Normalized manufacturer ids
     */
    public function __construct(
        public ?string $search = null,
        public ?int $group = null,
        public ?int $species = null,
        #[Assert\All(new Assert\Type('integer'))]
        public array $manufacturers = [],
        public int $page = self::DEFAULT_PAGE,
        public int $limit = self::DEFAULT_LIMIT
    ) {
        $this->search = $this->search !== null ? trim($this->search) : null;
        if ($this->search === '') {
            $this->search = null;
        }

        $this->page = max(1, $this->page);
        $this->limit = max(1, $this->limit);
    }
}
