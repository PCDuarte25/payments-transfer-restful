<?php

namespace App\Persistence\Implementation\Repositories;

use App\Models\Transaction;
use App\Persistence\Interfaces\Repositories\TransactionsRepositoryInterface;

/**
 * Class TransactionsRepository
 *
 * Eloquent implementation of the TransactionsRepositoryInterface.
 * Responsible for the persistence of transaction history records.
 *
 * @package App\Persistence\Implementation\Repositories
 */
class TransactionsRepository implements TransactionsRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(array $data): ?Transaction
    {
        return Transaction::create($data);
    }
}

