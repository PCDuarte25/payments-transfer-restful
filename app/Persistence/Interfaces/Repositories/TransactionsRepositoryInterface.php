<?php

namespace App\Persistence\Interfaces\Repositories;

use App\Models\Transaction;

/**
 * Interface TransactionsRepositoryInterface
 *
 * Defines the contract for persistence operations related to financial transactions.
 * This interface is responsible for recording the history of transfers between users.
 *
 * @package App\Persistence\Interfaces\Repositories
 */
interface TransactionsRepositoryInterface
{
    /**
     * Persists a new transaction record in the database.
     *
     * @param array $data Attributes for the transaction (payer_id, recipient_id, amount).
     * @return Transaction|null The created Transaction instance or null if persistence fails.
     */
    public function create(array $data): ?Transaction;
}
