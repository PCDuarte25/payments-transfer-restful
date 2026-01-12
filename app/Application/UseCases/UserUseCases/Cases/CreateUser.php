<?php

namespace App\Application\UseCases\UserUseCases\Cases;

use App\Persistence\Interfaces\Repositories\FundsRepositoryInterface;
use App\Persistence\Interfaces\Repositories\UsersRepositoryInterface;
use Exception;

/**
 * Class CreateUser
 *
 * Handles the logic for registering a new user in the system.
 * This includes password encryption, uniqueness validation, and the
 * initialization of the user's financial balance record.
 *
 * @package App\Application\UseCases\UserUseCases\Cases
 */
class CreateUser
{
    /**
     * Create a new create user use case instance.
     *
     * @param UsersRepositoryInterface $usersRepository Handles user data retrieval and persistence.
     * @param FundsRepositoryInterface $fundsRepository Manages financial balances and wallet updates.
     */
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private FundsRepositoryInterface $fundsRepository,
    )
    {}

    /**
     * Executes the user creation process.
     *
     * @param array $data Input data containing 'password', 'document', 'email', etc.
     * @return array The created user model converted to an array.
     * @throws Exception If the document/email exists or if persistence fails.
     */
    public function execute(array $data): array
    {
        // Hash password for security
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['password'] = $password;

        // Validate Uniqueness
        if ($this->usersRepository->getByDocument($data['document'])) {
            throw new Exception("Usuário com este documento já existe.", 400);
        }

        if ($this->usersRepository->getByEmail($data['email'])) {
            throw new Exception("Usuário com este email já existe.", 400);
        }

        try {
            $this->usersRepository->beginTransaction();

            // Persist User
            $user = $this->usersRepository->create($data);

            // Initialize User Funds (Wallet)
            $this->fundsRepository->create(['user_id' => $user->id]);

            $this->usersRepository->commitTransaction();

            return $user->toArray();
        } catch (Exception $e) {
            $this->usersRepository->rollBackTransaction();
            throw new Exception("Erro ao criar usuário: " . $e->getMessage(), 500);
        }
    }
}
