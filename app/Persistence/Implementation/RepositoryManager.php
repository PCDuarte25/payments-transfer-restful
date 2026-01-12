<?php

namespace App\Persistence\Implementation;

use App\Persistence\Implementation\Repositories\FundsRepository;
use App\Persistence\Implementation\Repositories\TransactionsRepository;
use App\Persistence\Implementation\Repositories\UsersRepository;
use App\Persistence\Interfaces\RepositoryManagerInterface;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class RepositoryManager
 *
 * Concrete implementation of the RepositoryManagerInterface.
 * Manages repository instantiation and database transaction lifecycle using Laravel's DB facade.
 *
 * @package App\Persistence\Implementation
 */
abstract class RepositoryManager implements RepositoryManagerInterface
{
    /**
     * The active database connection instance.
     *
     * @var ConnectionInterface
     */
    private ConnectionInterface $dbConnection;

    /**
     * RepositoryManager constructor.
     * Initializes the database connection.
     */
    public function __construct()
    {
        $this->dbConnection = DB::connection();
    }

    /**
     * {@inheritDoc}
     */
    public function beginTransaction(): void
    {
        $this->dbConnection->beginTransaction();
    }

    /**
     * {@inheritDoc}
     */
    public function commitTransaction(): void
    {
        $this->dbConnection->commit();
    }

    /**
     * {@inheritDoc}
     */
    public function rollbackTransaction(): void
    {
        $this->dbConnection->rollBack();
    }
}
