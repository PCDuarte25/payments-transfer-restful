<?php

namespace App\Persistence\Implementation\Repositories;

use App\Models\Fund;
use App\Persistence\Interfaces\Repositories\FundsRepositoryInterface;

class FundsRepository implements FundsRepositoryInterface
{
    public function create(array $data): ?Fund
    {
        return Fund::create($data);
    }
}

