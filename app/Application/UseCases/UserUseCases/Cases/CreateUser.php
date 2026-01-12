<?php

namespace App\Application\UseCases\UserUseCases\Cases;

use App\Persistence\Interfaces\RepositoryManagerInterface;
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
     * The repository manager providing access to user and fund persistence.
     *
     * @var RepositoryManagerInterface
     */
    private RepositoryManagerInterface $repositoryManager;

    /**
     * CreateUser constructor.
     *
     * @param RepositoryManagerInterface $repositoryManager
     */
    public function __construct(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

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

        $usersRepository = $this->repositoryManager->getUsersRepository();
        $fundsRepository = $this->repositoryManager->getFundsRepository();

        // Validate Uniqueness
        if ($usersRepository->getFromDocument($data['document'])) {
            throw new Exception("Usuário com este documento já existe.", 400);
        }

        if ($usersRepository->getFromEmail($data['email'])) {
            throw new Exception("Usuário com este email já existe.", 400);
        }

        try {
            $this->repositoryManager->beginTransaction();

            // Persist User
            $user = $usersRepository->create($data);

            // Initialize User Funds (Wallet)
            $fundsRepository->create(['user_id' => $user->id]);

            $this->repositoryManager->commitTransaction();

            return $user->toArray();
        } catch (Exception $e) {
            $this->repositoryManager->rollBackTransaction();
            throw new Exception("Erro ao criar usuário: " . $e->getMessage(), 500);
        }
    }
}
