<?php
namespace App\Application\UseCases\UserUseCases;

use App\Application\UseCases\UserUseCases\Cases\CreateUser;
use App\Application\UseCases\UserUseCases\Cases\DeleteUser;
use App\Application\UseCases\UserUseCases\Cases\UpdateUser;

class UserUseCase
{
    private CreateUser $createUser;
    private UpdateUser $updateUser;
    private DeleteUser $deleteUser;

    public function __construct(
        CreateUser $createUser,
        UpdateUser $updateUser,
        DeleteUser $deleteUser
    )
    {
        $this->createUser = $createUser;
        $this->updateUser = $updateUser;
        $this->deleteUser = $deleteUser;
    }

    public function createUser(array $data): array {
        return $this->createUser->execute($data);
    }

    public function updateUser(string $id, array $data): array {
        return $this->updateUser->execute($id, $data);
    }

    public function deleteUser(string $id): void {
        $this->deleteUser->execute($id);
    }
}
