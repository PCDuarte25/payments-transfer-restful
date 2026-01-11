<?php

namespace App\Http\Controllers\api\v1;

use App\Application\UseCases\TransactionUseCases\TransactionUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;

class TransactionController extends Controller
{
    private TransactionUseCase $transactionUseCase;

    public function __construct(TransactionUseCase $transactionUseCase)
    {
        $this->transactionUseCase = $transactionUseCase;
    }

    public function createTransaction(TransactionRequest $request)
    {
        try {
            $data = $request->validated();

            $transaction = $this->transactionUseCase->createTransaction($data);

            return response()->json($transaction, 200);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
