<?php
namespace App\Application\UseCases\UserUseCases;

use App\Application\UseCases\UserUseCases\Cases\CreateUser;
use App\Application\UseCases\UserUseCases\Cases\DeleteUser;
use App\Application\UseCases\UserUseCases\Cases\UpdateUser;

/**
 * Class UserUseCase
 *
 * This service acts as the primary gateway for user management operations.
 * It orchestrates specific use cases for creating, updating, and deleting users,
 * ensuring a clean separation between the delivery layer and domain logic.
 *
 * @package App\Application\UseCases\UserUseCases
 */
class UserUseCase
{
    /**
     * @var CreateUser Logic for user registration.
     */
    private CreateUser $createUser;

    /**
     * @var UpdateUser Logic for user profile modification.
     */
    private UpdateUser $updateUser;

    /**
     * @var DeleteUser Logic for user removal.
     */
    private DeleteUser $deleteUser;

    /**
     * UserUseCase constructor.
     *
     * @param CreateUser $createUser
     * @param UpdateUser $updateUser
     * @param DeleteUser $deleteUser
     */
    public function __construct(
        CreateUser $createUser,
        UpdateUser $updateUser,
        DeleteUser $deleteUser
    )
    {
        $this->createUser = $createUser;
        $this->updateUser = $updateUser;
        $this->deleteUser = $deleteUser;
    }

    /**
     * Registers a new user in the system.
     *
     * @param array $data User profile information and credentials.
     * @return array The created user data.
     * @throws \Exception If validation fails or registration is denied.
     */
    public function createUser(array $data): array {
        return $this->createUser->execute($data);
    }

    /**
     * Modifies an existing user's information.
     *
     * @param string $userId The unique identifier of the user.
     * @param array $data The updated fields for the user profile.
     * @return array The updated user data.
     * @throws \Exception If the user is not found or unique constraints are violated.
     */
    public function updateUser(string $userId, array $data): array {
        return $this->updateUser->execute($userId, $data);
    }

    /**
     * Soft delete a user and their associated data from the system.
     *
     * @param string $userId The unique identifier of the user to be deleted.
     * @return void
     * @throws \Exception If the user is not found or a database error occurs.
     */
    public function deleteUser(string $userId): void {
        $this->deleteUser->execute($userId);
    }
}
