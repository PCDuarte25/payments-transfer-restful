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

        if ($this->repositoryManager->getUsersRepository()->getFromDocument($data['document'])) {
            throw new \Exception("Usuário com este documento já existe.", 400);
        }

        return [];
    }
}
