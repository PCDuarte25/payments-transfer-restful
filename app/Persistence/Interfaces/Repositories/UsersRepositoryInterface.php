<?php

namespace App\Persistence\Interfaces\Repositories;

use App\Models\User;

interface UsersRepositoryInterface
{
    public function getFromDocument(string $document): ?User;
}
