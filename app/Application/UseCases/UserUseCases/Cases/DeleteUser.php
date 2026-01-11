<?php

namespace App\Application\UseCases\UserUseCases\Cases;

use App\Persistence\Interfaces\RepositoryManagerInterface;

class DeleteUser
{
    private RepositoryManagerInterface $repositoryManager;

    public function __construct(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function execute(string $id): void
    {
        $usersRepository = $this->repositoryManager->getUsersRepository();
        $fundsRepository = $this->repositoryManager->getFundsRepository();

        $user = $usersRepository->getFromId($id);
        if (!$user) {
            throw new \Exception("Usuário não encontrado.", 404);
        }

        try {
            $this->repositoryManager->beginTransaction();
            $usersRepository->delete($id);
            $fundsRepository->deleteByUserId($id);
            $this->repositoryManager->commitTransaction();
        } catch (\Exception $e) {
            $this->repositoryManager->rollbackTransaction();
            throw new \Exception("Erro ao deletar usuário: " . $e->getMessage(), 500);
        }
    }
}
