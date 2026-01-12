<?php

namespace App\Application\UseCases\UserUseCases\Cases;

use App\Persistence\Interfaces\RepositoryManagerInterface;
use Exception;

/**
 * Class DeleteUser
 *
 * This use case handles the logic for soft delete a user from the system.
 * It ensures that all associated data, specifically the user's funds,
 * are soft deleted simultaneously to maintain database integrity.
 *
 * @package App\Application\UseCases\UserUseCases\Cases
 */
class DeleteUser
{
    /**
     * Manager for database repositories and transaction control.
     *
     * @var RepositoryManagerInterface
     */
    private RepositoryManagerInterface $repositoryManager;

    /**
     * DeleteUser constructor.
     *
     * @param RepositoryManagerInterface $repositoryManager
     */
    public function __construct(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * Executes the user and associated funds deletion.
     *
     * @param string $userId The unique identifier of the user to be deleted.
     * @return void
     * @throws Exception If the user is not found or if a database error occurs.
     */
    public function execute(string $userId): void
    {
        $usersRepository = $this->repositoryManager->getUsersRepository();
        $fundsRepository = $this->repositoryManager->getFundsRepository();

        // Verify existence
        $user = $usersRepository->getFromId($userId);
        if (!$user) {
            throw new Exception("Usuário não encontrado.", 404);
        }

        try {
            $this->repositoryManager->beginTransaction();

            // Soft Delete User
            $usersRepository->delete($userId);

            // Soft Delete Related Funds
            $fundsRepository->deleteByUserId($userId);

            $this->repositoryManager->commitTransaction();
        } catch (Exception $e) {
            // Rollback on Failure
            $this->repositoryManager->rollbackTransaction();
            throw new Exception("Erro ao deletar usuário: " . $e->getMessage(), 500);
        }
    }
}
