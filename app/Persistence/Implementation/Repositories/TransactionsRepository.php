<?php

namespace App\Persistence\Implementation\Repositories;

use App\Models\Transaction;
use App\Persistence\Interfaces\Repositories\TransactionsRepositoryInterface;

class TransactionsRepository implements TransactionsRepositoryInterface
{
    public function create(array $data): ?Transaction
    {
        return Transaction::create($data);
    }
}

