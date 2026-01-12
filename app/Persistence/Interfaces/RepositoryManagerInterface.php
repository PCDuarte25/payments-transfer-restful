<?php

namespace App\Persistence\Interfaces;

use App\Persistence\Implementation\Repositories\FundsRepository;
use App\Persistence\Implementation\Repositories\TransactionsRepository;
use App\Persistence\Implementation\Repositories\UsersRepository;

/**
 * Interface RepositoryManagerInterface
 *
 * This interface implements the Unit of Work pattern. It coordinates the
 * different repositories and manages database transactions to ensure
 * data integrity across the application.
 *
 * @package App\Persistence\Interfaces
 */
interface RepositoryManagerInterface
{
    /**
     * Retrieves the instance of the Users repository.
     *
     * @return UsersRepository
     */
    public function getUsersRepository(): UsersRepository;

    /**
     * Retrieves the instance of the Funds (Wallet) repository.
     *
     * @return FundsRepository
     */
    public function getFundsRepository(): FundsRepository;

    /**
     * Retrieves the instance of the Transactions repository.
     *
     * @return TransactionsRepository
     */
    public function getTransactionsRepository(): TransactionsRepository;

    /**
     * Starts a new database transaction.
     *
     * Use this before performing multiple related write operations
     * to ensure atomicity.
     *
     * @return void
     */
    public function beginTransaction(): void;

    /**
     * Commits the current database transaction.
     *
     * Saves all changes made during the transaction to the database permanently.
     *
     * @return void
     */
    public function commitTransaction(): void;

    /**
     * Rolls back the current database transaction.
     *
     * Reverts all changes made during the transaction if an error occurs.
     *
     * @return void
     */
    public function rollbackTransaction(): void;
}
