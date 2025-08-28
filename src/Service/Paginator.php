<?php

namespace App\Service;

final class Paginator
{
    /**
     * Paginate data and return metadata array.
     *
     * @param int $total    Total items count
     * @param int $page     Current page (1-based)
     * @param int $limit    Items per page
     * @param array{max_visible?: int} $opts
     *
     * @return array{
     *   currentPage: int,
     *   limit: int,
     *   total: int,
     *   totalPages: int,
     *   offset: int,
     *   firstItemIndex: int,
     *   lastItemIndex: int,
     *   hasPrevious: bool,
     *   hasNext: bool,
     *   previousPage: int|null,
     *   nextPage: int|null,
     *   pages: list<int>
     * }
     */
    public function paginate(int $total, int $page, int $limit, array $opts = []): array
    {
        $maxVisible = $opts['max_visible'] ?? 7;

        $limit = max(1, $limit);
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

        return [
            'currentPage' => $page,
            'limit' => $limit,
            'total' => $total,
            'totalPages' => $totalPages,
            'offset' => $offset,
            'firstItemIndex' => $firstItemIndex,
            'lastItemIndex' => $lastItemIndex,
            'hasPrevious' => $page > 1,
            'hasNext' => $page < $totalPages,
            'previousPage' => $page > 1 ? $page - 1 : null,
            'nextPage' => $page < $totalPages ? $page + 1 : null,
            'pages' => $pages,
        ];
    }
}
