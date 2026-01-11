<?php
namespace App\Application\UseCases\UserUseCases;

use App\Application\UseCases\UserUseCases\Cases\CreateUser;
use App\Application\UseCases\UserUseCases\Cases\UpdateUser;

class UserUseCase
{
    private CreateUser $createUser;
    private UpdateUser $updateUser;

    public function __construct(
        CreateUser $createUser,
        UpdateUser $updateUser
    )
    {
        $this->createUser = $createUser;
        $this->updateUser = $updateUser;
    }

    public function createUser(array $data): array {
        return $this->createUser->execute($data);
    }

    public function updateUser(string $id, array $data): array {
        return $this->updateUser->execute($id, $data);
    }
}
