<?php

namespace App\Application\UseCases\TransactionUseCases\Cases;

use App\Events\TransactionCompleted;
use App\Models\User;
use App\Persistence\Interfaces\RepositoryManagerInterface;
use App\Services\TransactionAuthorizationService;
use Exception;

/**
 * Class CreateTransaction
 *
 * Orchestrates the process of transferring funds between a payer and a recipient.
 * This use case handles validation, external authorization, financial balance updates,
 * and transaction persistence.
 *
 * @package App\Application\UseCases\TransactionUseCases\Cases
 */
class CreateTransaction
{
    /**
     * Manager for database repositories and transaction control.
     *
     * @var RepositoryManagerInterface
     */
    private RepositoryManagerInterface $repositoryManager;

    /**
     * Service used to authorize the transaction via external provider.
     *
     * @var TransactionAuthorizationService
     */
    private TransactionAuthorizationService $authorizationService;

    /**
     * CreateTransaction constructor.
     *
     * @param RepositoryManagerInterface $repositoryManager
     * @param TransactionAuthorizationService $authorizationService
     */
    public function __construct(
        RepositoryManagerInterface $repositoryManager,
        TransactionAuthorizationService $authorizationService
    )
    {
        $this->authorizationService = $authorizationService;
        $this->repositoryManager = $repositoryManager;
    }

    /**
     * Executes the transaction process.
     *
     * @param array $data Expected keys: 'payer_id', 'recipient_id', 'amount'.
     * @return array Summary of the completed transaction and updated balances.
     * @throws Exception If validation fails, authorization is denied, or a database error occurs.
     */
    public function execute(array $data): array
    {
        $usersRepository = $this->repositoryManager->getUsersRepository();
        $transactionsRepository = $this->repositoryManager->getTransactionsRepository();
        $fundsRepository = $this->repositoryManager->getFundsRepository();

        $payer = $usersRepository->getFromId($data['payer_id']);
        $recipient = $usersRepository->getFromId($data['recipient_id']);

        // Validate business rules
        $this->validateUsers($payer, $recipient, $data['amount']);

        // External Authorization
        if (!$this->authorizationService->authorize()) {
            throw new Exception("Transação não autorizada pelo serviço externo.", 403);
        }

        try {
            // Create transaction record
            $this->repositoryManager->beginTransaction();
            $transaction = $transactionsRepository->create([
                'payer_id' => $payer->id,
                'recipient_id' => $recipient->id,
                'amount' => $data['amount'],
            ]);

            // Update balances
            $this->updateFunds($payer, $recipient, $data['amount']);

            $this->repositoryManager->commitTransaction();
        } catch (Exception $e) {
            $this->repositoryManager->rollBackTransaction();
            throw new Exception("Erro ao criar transação: " . $e->getMessage(), 500);
        }

        // Trigger post-transaction events
        event(new TransactionCompleted(
            $transaction->id,
            $recipient->id,
            $transaction->amount
        ));

        $payerFund = $fundsRepository->getFundByUserId($payer->id);
        $recipientFund = $fundsRepository->getFundByUserId($recipient->id);
        return [
            'transaction_id' => $transaction->id,
            'payer_id' => $transaction->payer_id,
            'payer_new_balance' => $payerFund->balance,
            'recipient_id' => $transaction->recipient_id,
            'recipient_new_balance' => $recipientFund->balance,
            'amount' => $transaction->amount,
        ];
    }

    /**
     * Validates the participants and the feasibility of the transaction.
     *
     * @param User|null $payer The user sending the funds.
     * @param User|null $recipient The user receiving the funds.
     * @param float $amount The transaction value.
     * @return void
     * @throws Exception If any business rule is violated.
     */
    private function validateUsers(?User $payer, User $recipient, float $amount): void
    {
        if (!$payer) {
            throw new Exception("Usuário pagador não encontrado.", 404);
        }

        if (!$recipient) {
            throw new Exception("Usuário recebedor não encontrado.", 404);
        }

        if ($payer->isMerchant()) {
            throw new Exception("Lojistas não podem realizar pagamentos.", 403);
        }

        if ($payer->id === $recipient->id) {
            throw new Exception("O pagador e o recebedor não podem ser o mesmo usuário.", 400);
        }

        $payerFund = $this->repositoryManager->getFundsRepository()->getFundByUserId($payer->id);
        if ($payerFund->balance < $amount) {
            throw new Exception("Saldo insuficiente para realizar a transação.", 400);
        }
    }

    /**
     * Updates the financial balances for both transaction participants.
     *
     * @param User $payer
     * @param User $recipient
     * @param int $amount
     * @return void
     */
    private function updateFunds(User $payer, User $recipient, int $amount): void
    {
        $fundsRepository = $this->repositoryManager->getFundsRepository();

        $payerFund = $fundsRepository->getFundByUserId($payer->id);
        $fundsRepository->updateFundByUserId($payer->id, [
            'balance' => $payerFund->balance - $amount,
        ]);

        $recipientFund = $fundsRepository->getFundByUserId($recipient->id);
        $fundsRepository->updateFundByUserId($recipient->id, [
            'balance' => $recipientFund->balance + $amount,
        ]);
    }
}
