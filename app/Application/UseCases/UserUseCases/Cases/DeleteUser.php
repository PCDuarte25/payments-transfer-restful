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

        $user = $usersRepository->getFromId($id);
        if (!$user) {
            throw new \Exception("Usuário não encontrado.", 404);
        }

        $this->repositoryManager->beginTransaction();
        $usersRepository->delete($id);
        $this->repositoryManager->commitTransaction();
    }
}
