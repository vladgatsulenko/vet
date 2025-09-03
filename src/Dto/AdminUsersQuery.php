<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class AdminUsersQuery
{
    #[Assert\Positive(message: 'Page must be a positive integer.')]
    public int $page = 1;

    #[Assert\Range(min: 1, max: 100, notInRangeMessage: 'Limit must be between {{ min }} and {{ max }}.')]
    public int $limit = 10;
}
