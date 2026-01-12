<?php

namespace App\Application\UseCases\UserUseCases\Cases;

use App\Persistence\Interfaces\RepositoryManagerInterface;
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
     * Manager for database repositories and transaction control.
     *
     * @var RepositoryManagerInterface
     */
    private RepositoryManagerInterface $repositoryManager;

    /**
     * UpdateUser constructor.
     *
     * @param RepositoryManagerInterface $repositoryManager
     */
    public function __construct(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

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
        $usersRepository = $this->repositoryManager->getUsersRepository();

        // Locate existing user
        $user = $usersRepository->getFromId($userId);
        if (!$user) {
            throw new Exception("Não foi possível encontrar um usuário.", 400);
        }

        // Validate Document Uniqueness
        // Only checks if the document is being changed and if the new one exists elsewhere.
        if ($data['document'] !== $user->document && $usersRepository->getFromDocument($data['document'])) {
            throw new Exception("Usuário com este documento já existe.", 400);
        }

        // Validate Email Uniqueness
        // Only checks if the email is being changed and if the new one exists elsewhere.
        if ($data['email'] !== $user->email && $usersRepository->getFromEmail($data['email'])) {
            throw new Exception("Usuário com este email já existe.", 400);
        }

        try {
            $this->repositoryManager->beginTransaction();

            // Persist Changes
            $user = $usersRepository->update($user, $data);

            $this->repositoryManager->commitTransaction();

            return $user->toArray();
        } catch (Exception $e) {
            $this->repositoryManager->rollbackTransaction();
            throw new Exception("Erro ao atualizar o usuário: " . $e->getMessage(), 500);
        }
    }
}
