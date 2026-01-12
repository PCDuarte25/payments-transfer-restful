<?php

namespace App\Persistence\Interfaces\Repositories;

use App\Models\User;

interface UsersRepositoryInterface
{
    public function getFromDocument(string $document): ?User;
    public function getFromEmail(string $email): ?User;
    public function getFromId(string $userId): ?User;
    public function create(array $data): ?User;
    public function update(User $user, array $data): ?User;
    public function delete(string $userId): void;
}
