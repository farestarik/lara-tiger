<?php

namespace App\Utilities\UsersFilters;

use App\Utilities\FilterContract;

class Created_at implements FilterContract
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function handle($value): void
    {
        $this->query->where('created_at', $value);
    }
}