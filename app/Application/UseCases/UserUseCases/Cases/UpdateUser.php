<?php

namespace App\Application\UseCases\UserUseCases\Cases;

use App\Persistence\Interfaces\RepositoryManagerInterface;
use Exception;

class UpdateUser
{
    private RepositoryManagerInterface $repositoryManager;

    public function __construct(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function execute(string $userId, array $data): array
    {
        $usersRepository = $this->repositoryManager->getUsersRepository();

        $user = $usersRepository->getFromId($userId);
        if (!$user) {
            throw new Exception("Não foi possível encontrar um usuário.", 400);
        }

        if ($data['document'] !== $user->document && $usersRepository->getFromDocument($data['document'])) {
            throw new Exception("Usuário com este documento já existe.", 400);
        }

        if ($data['email'] !== $user->email && $usersRepository->getFromEmail($data['email'])) {
            throw new Exception("Usuário com este email já existe.", 400);
        }

        $this->repositoryManager->beginTransaction();
        $user = $usersRepository->update($user, $data);
        $this->repositoryManager->commitTransaction();

        return $user->toArray();
    }
}
