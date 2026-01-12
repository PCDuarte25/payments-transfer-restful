<?php

namespace App\Application\UseCases\UserUseCases\Cases;

use App\Persistence\Interfaces\RepositoryManagerInterface;
use Exception;
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
        $fundsRepository = $this->repositoryManager->getFundsRepository();

        if ($usersRepository->getFromDocument($data['document'])) {
            throw new Exception("Usuário com este documento já existe.", 400);
        }

        if ($usersRepository->getFromEmail($data['email'])) {
            throw new Exception("Usuário com este email já existe.", 400);
        }

        try {
            $this->repositoryManager->beginTransaction();

            $user = $usersRepository->create($data);
            $fundsRepository->create(['user_id' => $user->id]);

            $this->repositoryManager->commitTransaction();
        } catch (\Exception $e) {
            $this->repositoryManager->rollBackTransaction();
            throw new Exception("Erro ao criar usuário: " . $e->getMessage(), 500);
        }

        return $user->toArray();
    }
}
