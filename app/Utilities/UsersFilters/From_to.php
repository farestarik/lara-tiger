<?php

namespace App\Utilities\UsersFilters;

use Carbon\Carbon;
use App\Utilities\FilterContract;

class From_to implements FilterContract
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function handle($value): void
    {

        $from = @$value[0];
        $to = @$value[1];

        if(!$from){
            $from = Carbon::now()->toDateTimeString();
        }

        if(!$to){
            $to = Carbon::now()->toDateTimeString();
        }

        $this->query->whereBetween('created_at', [
            $from,
            $to
        ]);
    }
}