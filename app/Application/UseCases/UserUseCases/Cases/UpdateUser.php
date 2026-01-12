<?php

namespace App\Application\UseCases\UserUseCases\Cases;

use App\Persistence\Interfaces\Repositories\UsersRepositoryInterface;
use Exception;

/**
 * Class UpdateUser
 *
 * Responsible for updating existing user information.
 * This use case validates the existence of the user and ensures that
 * sensitive unique fields (email and document) do not conflict with other users.
 *
 * @package App\Application\UseCases\UserUseCases\Cases
 */
class UpdateUser
{
    /**
     * Create a new update user use case instance.
     *
     * @param UsersRepositoryInterface $usersRepository Handles user data retrieval and persistence.
     */
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {}

    /**
     * Executes the user update logic.
     *
     * @param string $userId The ID of the user to be updated.
     * @param array $data The new data to be applied (e.g., name, email, document).
     * @return array The updated user model converted to an array.
     * @throws Exception If the user is not found or if unique constraints are violated.
     */
    public function execute(string $userId, array $data): array
    {
        // Locate existing user
        $user = $this->usersRepository->getFromId($userId);
        if (!$user) {
            throw new Exception("Não foi possível encontrar um usuário.", 400);
        }

        // Validate Document Uniqueness
        // Only checks if the document is being changed and if the new one exists elsewhere.
        if ($data['document'] !== $user->document && $this->usersRepository->getFromDocument($data['document'])) {
            throw new Exception("Usuário com este documento já existe.", 400);
        }

        // Validate Email Uniqueness
        // Only checks if the email is being changed and if the new one exists elsewhere.
        if ($data['email'] !== $user->email && $this->usersRepository->getFromEmail($data['email'])) {
            throw new Exception("Usuário com este email já existe.", 400);
        }

        try {
            $this->usersRepository->beginTransaction();

            // Persist Changes
            $user = $this->usersRepository->update($user, $data);

            $this->usersRepository->commitTransaction();

            return $user->toArray();
        } catch (Exception $e) {
            $this->usersRepository->rollbackTransaction();
            throw new Exception("Erro ao atualizar o usuário: " . $e->getMessage(), 500);
        }
    }
}
