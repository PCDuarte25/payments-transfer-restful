<?php

namespace App\Application\UseCases\UserUseCases\Cases;

use App\Persistence\Interfaces\Repositories\FundsRepositoryInterface;
use App\Persistence\Interfaces\Repositories\UsersRepositoryInterface;
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
     * Create a new delete user use case instance.
     *
     * @param UsersRepositoryInterface $usersRepository Handles user data retrieval and persistence.
     * @param FundsRepositoryInterface $fundsRepository Manages financial balances and wallet updates.
     */
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private FundsRepositoryInterface $fundsRepository,
    )
    {}

    /**
     * Executes the user and associated funds deletion.
     *
     * @param string $userId The unique identifier of the user to be deleted.
     * @return void
     * @throws Exception If the user is not found or if a database error occurs.
     */
    public function execute(string $userId): void
    {
        // Verify existence
        $user = $this->usersRepository->getFromId($userId);
        if (!$user) {
            throw new Exception("Usuário não encontrado.", 404);
        }

        try {
            $this->usersRepository->beginTransaction();

            // Soft Delete User
            $this->usersRepository->delete($userId);

            // Soft Delete Related Funds
            $this->fundsRepository->deleteByUserId($userId);

            $this->usersRepository->commitTransaction();
        } catch (Exception $e) {
            // Rollback on Failure
            $this->usersRepository->rollbackTransaction();
            throw new Exception("Erro ao deletar usuário: " . $e->getMessage(), 500);
        }
    }
}
