<?php

namespace App\Persistence\Implementation;

use App\Persistence\Implementation\Repositories\FundsRepository;
use App\Persistence\Implementation\Repositories\TransactionsRepository;
use App\Persistence\Implementation\Repositories\UsersRepository;
use App\Persistence\Interfaces\RepositoryManagerInterface;
use Illuminate\Support\Facades\DB;

class RepositoryManager implements RepositoryManagerInterface
{
    private $dbConnection;

    public function __construct()
    {
        $this->dbConnection = DB::connection();
    }

    public function getUsersRepository(): UsersRepository
    {
        return new UsersRepository();
    }

    public function getFundsRepository(): FundsRepository
    {
        return new FundsRepository();
    }

    public function getTransactionsRepository(): TransactionsRepository
    {
        return new TransactionsRepository();
    }

    public function beginTransaction(): void
    {
        $this->dbConnection->beginTransaction();
    }

    public function commitTransaction(): void
    {
        $this->dbConnection->commit();
    }

    public function rollbackTransaction(): void
    {
        $this->dbConnection->rollBack();
    }
}
