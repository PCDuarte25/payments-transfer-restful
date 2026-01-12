<?php

namespace App\Persistence\Implementation\Repositories;

use App\Models\User;
use App\Persistence\Implementation\RepositoryManager;
use App\Persistence\Interfaces\Repositories\UsersRepositoryInterface;

/**
 * Class UsersRepository
 *
 * Eloquent implementation of the UsersRepositoryInterface.
 * This class serves as the primary data provider for User entities.
 *
 * @package App\Persistence\Implementation\Repositories
 */
class UsersRepository extends RepositoryManager implements UsersRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function getByDocument(string $document): ?User
    {
        return User::where('document', $document)->first();
    }

    /**
     * {@inheritDoc}
     */
    public function getByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * {@inheritDoc}
     */
    public function getById(string $userId): ?User
    {
        return User::where('id', $userId)->first();
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): ?User
    {
        return User::create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function update(User $user, array $data): ?User
    {
        $user->fill($data);
        $user->save();

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $userId): void
    {
        User::where('id', $userId)->delete();
    }
}

