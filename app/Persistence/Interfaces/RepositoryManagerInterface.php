<?php

namespace App\Persistence\Interfaces;

use App\Persistence\Implementation\Repositories\FundsRepository;
use App\Persistence\Implementation\Repositories\UsersRepository;

interface RepositoryManagerInterface
{
    public function getUsersRepository(): UsersRepository;
    public function getFundsRepository(): FundsRepository;
    public function beginTransaction(): void;
    public function commitTransaction(): void;
    public function rollbackTransaction(): void;
}
