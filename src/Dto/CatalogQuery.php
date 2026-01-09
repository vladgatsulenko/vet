<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class CatalogQuery
{
    public const DEFAULT_PAGE = 1;
    public const DEFAULT_LIMIT = 12;

    public ?string $search;
    public ?int $group;
    public ?int $species;

    /** @var int[] */
    public array $manufacturers;

    public int $page;
    public int $limit;

    /**
     * @param array|int|string|null $manufacturers Raw manufacturers value from query (can be scalar or array)
     */
    public function __construct(
        ?string $search = null,
        ?int $group = null,
        ?int $species = null,
        $manufacturers = null,
        ?int $page = null,
        ?int $limit = null
    ) {
        $s = $search !== null ? trim($search) : null;
        $this->search = ($s === '') ? null : $s;

        $this->group = $group;
        $this->species = $species;

        $raw = $manufacturers ?? [];
        if (!is_array($raw)) {
            $raw = ($raw === null) ? [] : [$raw];
        }

        $ids = [];
        foreach ($raw as $v) {
            $val = filter_var($v, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            if ($val !== null) {
                $ids[] = $val;
            }
        }
        $this->manufacturers = array_values($ids);

        $this->page = max(1, (int) ($page ?? self::DEFAULT_PAGE));
        $this->limit = max(1, (int) ($limit ?? self::DEFAULT_LIMIT));
    }
}
