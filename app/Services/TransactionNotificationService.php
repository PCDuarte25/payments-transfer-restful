<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class TransactionNotificationService
 *
 * Handles communication with external notification providers to alert users
 * about transaction events.
 *
 * @package App\Services
 */
class TransactionNotificationService
{
    /**
     * Sends a notification to a specific user regarding a transaction.
     *
     * @param int $userId The unique identifier of the user to be notified.
     * @param int $amount The transaction amount involved (usually in cents).
     * @return void
     */
    public function notify(int $userId, int $amount): void
    {
        $response = Http::post('https://util.devi.tools/api/v1/notify', [
            'user_id' => $userId,
            'amount'  => $amount,
            'message' => "Você recebeu uma transferência de {$amount}."
        ]);

        if ($response->failed()) {
            Log::error('Failed to send transaction notification', [
                'service'  => 'transaction_notification',
                'user_id'  => $userId,
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

            return;
        }

        Log::info('Notification sent successfully', [
            'service'  => 'transaction_notification',
            'user_id'  => $userId,
            'response' => $response->json(),
        ]);
    }
}
