<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\UseCases\TransactionUseCases\TransactionUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use Exception;

/**
 * Class TransactionController
 *
 * Handles incoming HTTP requests related to financial transactions.
 * This controller resides in the API V1 namespace to ensure versioning stability.
 *
 * @package App\Http\Controllers\Api\V1
 */
class TransactionController extends Controller
{
    /**
     * The orchestrator for transaction-related business logic.
     *
     * @var TransactionUseCase
     */
    private TransactionUseCase $transactionUseCase;

    /**
     * TransactionController constructor.
     *
     * @param TransactionUseCase $transactionUseCase
     */
    public function __construct(TransactionUseCase $transactionUseCase)
    {
        $this->transactionUseCase = $transactionUseCase;
    }

    /**
     * Handle the creation of a new transaction.
     *
     * This method validates the incoming request, attempts to execute
     * the transaction use case, and returns a JSON response.
     *
     * @param TransactionRequest $request Validated request containing payer, recipient, and amount.
     * @return JsonResponse
     */
    public function createTransaction(TransactionRequest $request)
    {
        try {
            // Retrieve validated data from the Form Request
            $data = $request->validated();

            // Delegate to the Use Case
            $transaction = $this->transactionUseCase->createTransaction($data);

            // Return success response
            return response()->json($transaction, 201);
        }
        catch (Exception $e) {
            // Return error response with the specific message and status
            $status = $e->getCode() ?: 400;
            return response()->json(['error' => $e->getMessage()], $status);
        }
    }
}
