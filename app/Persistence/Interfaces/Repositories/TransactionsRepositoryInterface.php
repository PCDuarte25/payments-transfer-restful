<?php

namespace App\Persistence\Interfaces\Repositories;

use App\Models\Transaction;

interface TransactionsRepositoryInterface
{
    public function create(array $data): ?Transaction;
}
