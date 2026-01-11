<?php

namespace App\Application\UseCases\UserUseCases\Cases;

use App\Persistence\Interfaces\RepositoryManagerInterface;
use Illuminate\Http\Client\Response;

class CreateUser
{
    private RepositoryManagerInterface $repositoryManager;

    public function __construct(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function execute(array $data): array
    {
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['password'] = $password;

        $usersRepository = $this->repositoryManager->getUsersRepository();

        if ($usersRepository->getFromDocument($data['document'])) {
            throw new \Exception("Usuário com este documento já existe.", 400);
        }

        if ($usersRepository->getFromEmail($data['email'])) {
            throw new \Exception("Usuário com este email já existe.", 400);
        }

        $this->repositoryManager->beginTransaction();
        $user = $usersRepository->create($data);
        $this->repositoryManager->commitTransaction();

        return $user->toArray();

    }
}
