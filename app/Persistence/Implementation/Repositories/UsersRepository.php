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

    public function getFromEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getFromId(string $id): ?User
    {
        return User::where('id', $id)->first();
    }

    public function create(array $data): ?User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): ?User
    {
        $user->fill($data);
        $user->save();
        return $user;
    }

    public function delete(string $id): void
    {
        User::where('id', $id)->delete();
    }
}

