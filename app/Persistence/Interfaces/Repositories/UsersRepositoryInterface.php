<?php

namespace App\Persistence\Interfaces\Repositories;

use App\Models\User;

interface UsersRepositoryInterface
{
    public function getFromDocument(string $document): ?User;
    public function getFromEmail(string $email): ?User;
    public function create(array $data): ?User;
}
