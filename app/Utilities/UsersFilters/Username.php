<?php

namespace App\Utilities\UsersFilters;

use App\Utilities\FilterContract;

class Username implements FilterContract
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function handle($value): void
    {
        $this->query->where('username', $value);
    }
}