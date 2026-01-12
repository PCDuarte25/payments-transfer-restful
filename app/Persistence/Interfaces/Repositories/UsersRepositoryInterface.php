<?php

namespace App\Persistence\Interfaces\Repositories;

use App\Models\User;
use App\Persistence\Interfaces\RepositoryManagerInterface;

/**
 * Interface UsersRepositoryInterface
 *
 * Defines the contract for user persistence operations.
 * This interface handles the retrieval of users by unique identifiers
 * and manages the CRUD lifecycle for the User domain.
 *
 * @package App\Persistence\Interfaces\Repositories
 */
interface UsersRepositoryInterface extends RepositoryManagerInterface
{
    /**
     * Retrieve a user by their unique document (CPF/CNPJ).
     *
     * @param string $document The document string to search for.
     * @return User|null
     */
    public function getByDocument(string $document): ?User;

    /**
     * Retrieve a user by their unique email address.
     *
     * @param string $email The email address to search for.
     * @return User|null
     */
    public function getByEmail(string $email): ?User;

    /**
     * Retrieve a user by their primary unique identifier (ID).
     *
     * @param string $userId The UUID or primary key of the user.
     * @return User|null
     */
    public function getById(string $userId): ?User;

    /**
     * Persist a new user record in the database.
     *
     * @param array $data Attributes for the user (full_name, email, document, password, etc.).
     * @return User|null The created User instance or null on failure.
     */
    public function create(array $data): ?User;

    /**
     * Update an existing user's profile information.
     *
     * @param User $user The User instance to be updated.
     * @param array $data The new data to apply.
     * @return User|null The updated User instance or null on failure.
     */
    public function update(User $user, array $data): ?User;

    /**
     * Soft delete a user record from the system.
     *
     * @param string $userId The unique identifier of the user to delete.
     * @return void
     */
    public function delete(string $userId): void;
}
