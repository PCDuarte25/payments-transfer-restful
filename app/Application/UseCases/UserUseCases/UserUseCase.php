<?php
namespace App\Application\UseCases\UserUseCases;

use App\Application\UseCases\UserUseCases\Cases\CreateUser;

class UserUseCase
{
    private CreateUser $createUser;

    public function __construct(CreateUser $createUser)
    {
        $this->createUser = $createUser;
    }

    public function createUser(array $data): array {
        return $this->createUser->execute($data);
    }
}
