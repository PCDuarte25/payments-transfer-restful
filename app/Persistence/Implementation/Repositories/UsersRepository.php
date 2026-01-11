<?php

namespace App\Persistence\Implementation\Repositories;

use App\Models\User;
use App\Persistence\Interfaces\Repositories\UsersRepositoryInterface;

class UsersRepository implements UsersRepositoryInterface
{
    public function getFromDocument(string $document): ?User
    {
        return User::where('document', $document)->first();
    }
}

