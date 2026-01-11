<?php

namespace App\Persistence\Implementation;

use App\Persistence\Implementation\Repositories\UsersRepository;
use App\Persistence\Interfaces\RepositoryManagerInterface;

class RepositoryManager implements RepositoryManagerInterface
{
    public function __construct()
    {}

    public function getUsersRepository(): UsersRepository
    {
        return new UsersRepository();
    }

}
