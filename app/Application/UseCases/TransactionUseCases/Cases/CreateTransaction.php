<?php

namespace App\Application\UseCases\TransactionUseCases\Cases;

use App\Models\User;
use App\Persistence\Interfaces\RepositoryManagerInterface;

class CreateTransaction
{
    private RepositoryManagerInterface $repositoryManager;

    public function __construct(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function execute(array $data): array
    {
        $usersRepository = $this->repositoryManager->getUsersRepository();
        $transactionsRepository = $this->repositoryManager->getTransactionsRepository();
        $fundsRepository = $this->repositoryManager->getFundsRepository();

        $payer = $usersRepository->getFromId($data['payer_id']);
        $recipient = $usersRepository->getFromId($data['recipient_id']);

        $this->validateUsers($payer, $recipient, $data['amount']);

        try {
            $this->repositoryManager->beginTransaction();
            $transaction = $transactionsRepository->create([
                'payer_id' => $payer->id,
                'recipient_id' => $recipient->id,
                'amount' => $data['amount'],
            ]);

            $this->updateFunds($payer, $recipient, $data['amount']);

            $this->repositoryManager->commitTransaction();
        } catch (\Exception $e) {
            $this->repositoryManager->rollBackTransaction();
            throw new \Exception("Erro ao criar transação: " . $e->getMessage(), 500);
        }

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

    private function validateUsers(User $payer, User $recipient, int $amount): void
    {
        if (!$payer) {
            throw new \Exception("Usuário pagador não encontrado.", 404);
        }

        if (!$recipient) {
            throw new \Exception("Usuário recebedor não encontrado.", 404);
        }

        if ($payer->isMerchant()) {
            throw new \Exception("Lojistas não podem realizar pagamentos.", 403);
        }

        if ($payer->id === $recipient->id) {
            throw new \Exception("O pagador e o recebedor não podem ser o mesmo usuário.", 400);
        }

        $payerFund = $this->repositoryManager->getFundsRepository()->getFundByUserId($payer->id);
        if ($payerFund->balance < $amount) {
            throw new \Exception("Saldo insuficiente para realizar a transação.", 400);
        }
    }

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
