<?php

namespace App\Service;

use App\Dto\Pagination;

final class Paginator
{
    public const DEFAULT_MAX_PAGES_VISIBLE = 7;
    public const MAX_LIMIT = 100;

    public function paginate(int $total, int $page, int $limit, int $maxVisible = self::DEFAULT_MAX_PAGES_VISIBLE): Pagination
    {
        $maxVisible = max(1, $maxVisible);
        $limit = min(self::MAX_LIMIT, max(1, $limit));
        $page  = max(1, $page);

        $totalPages = (int) max(1, ceil($total / $limit));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $limit;

        if ($totalPages <= $maxVisible) {
            $start = 1;
            $end = $totalPages;
        } else {
            $half = (int) floor($maxVisible / 2);
            $start = $page - $half;
            $end = $page + $half;

            if ($start < 1) {
                $start = 1;
                $end = $maxVisible;
            }
            if ($end > $totalPages) {
                $end = $totalPages;
                $start = $totalPages - $maxVisible + 1;
                if ($start < 1) {
                    $start = 1;
                }
            }
        }

        $pages = [];
        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }

        $firstItemIndex = $total === 0 ? 0 : $offset + 1;
        $lastItemIndex = min($total, $offset + $limit);

        return new Pagination(
            $page,
            $limit,
            $total,
            $totalPages,
            $offset,
            $firstItemIndex,
            $lastItemIndex,
            $page > 1,
            $page < $totalPages,
            $page > 1 ? $page - 1 : null,
            $page < $totalPages ? $page + 1 : null,
            $pages
        );
    }
}
