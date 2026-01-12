<?php

namespace App\Application\UseCases\UserUseCases\Cases;

use App\Persistence\Interfaces\RepositoryManagerInterface;
use Exception;

class DeleteUser
{
    private RepositoryManagerInterface $repositoryManager;

    public function __construct(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function execute(string $userId): void
    {
        $usersRepository = $this->repositoryManager->getUsersRepository();
        $fundsRepository = $this->repositoryManager->getFundsRepository();

        $user = $usersRepository->getFromId($userId);
        if (!$user) {
            throw new Exception("Usuário não encontrado.", 404);
        }

        try {
            $this->repositoryManager->beginTransaction();
            $usersRepository->delete($userId);
            $fundsRepository->deleteByUserId($userId);
            $this->repositoryManager->commitTransaction();
        } catch (\Exception $e) {
            $this->repositoryManager->rollbackTransaction();
            throw new Exception("Erro ao deletar usuário: " . $e->getMessage(), 500);
        }
    }
}
