<?php
namespace App\Application\UseCases\TransactionUseCases;

use App\Application\UseCases\TransactionUseCases\Cases\CreateTransaction;

class TransactionUseCase
{
    private CreateTransaction $createTransaction;

    public function __construct(
        CreateTransaction $createTransaction
    )
    {
        $this->createTransaction = $createTransaction;
    }

    public function createTransaction(array $data): array {
        return $this->createTransaction->execute($data);
    }

}
