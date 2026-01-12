<?php
namespace App\Application\UseCases\TransactionUseCases;

use App\Application\UseCases\TransactionUseCases\Cases\CreateTransaction;

/**
 * Class TransactionUseCase
 *
 * This class acts as the primary orchestrator for transaction-related actions.
 * It serves as a facade to access specific transaction use cases, facilitating
 * decoupling between the delivery mechanism (Controllers/Commands) and the domain logic.
 *
 * @package App\Application\UseCases\TransactionUseCases
 */
class TransactionUseCase
{
    /**
     * The specific use case for creating a transaction.
     *
     * @var CreateTransaction
     */
    private CreateTransaction $createTransaction;

    /**
     * TransactionUseCase constructor.
     *
     * @param CreateTransaction $createTransaction Dependency injection of the creation logic.
     */
    public function __construct(
        CreateTransaction $createTransaction
    )
    {
        $this->createTransaction = $createTransaction;
    }

    /**
     * Orchestrates the creation of a new transaction.
     *
     * Delegates the data to the CreateTransaction use case and returns
     * the processed result.
     *
     * @param array $data Input data including 'payer_id', 'recipient_id', and 'amount'.
     * @return array The processed transaction details and updated user balances.
     * @throws \Exception If the transaction logic encounters a validation or persistence error.
     */
    public function createTransaction(array $data): array {
        return $this->createTransaction->execute($data);
    }

}
