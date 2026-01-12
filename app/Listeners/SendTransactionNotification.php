<?php

namespace App\Listeners;

use App\Events\TransactionCompleted;
use App\Services\TransactionNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

/**
 * Class SendTransactionNotification
 *
 * Listens for the TransactionCompleted event and triggers a notification
 * to the recipient. This class is queued to prevent external latency
 * from affecting the main application flow.
 *
 * @package App\Listeners
 */
class SendTransactionNotification implements ShouldQueue
{
    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 10;

    /**
     * The notification service instance.
     *
     * @var TransactionNotificationService
     */
    private $notificationService;

    /**
     * Create the event listener.
     *
     * @param TransactionNotificationService $notificationService
     */
    public function __construct(
        TransactionNotificationService $notificationService
    )
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionCompleted $event): void
    {
        try {
            $this->notificationService->notify(
                $event->recipientId,
                $event->amount
            );
        } catch (\Exception $e) {
            Log::error("Failed to send transaction notification", [
                'transaction_id' => $event->transactionId,
                'recipient_id'   => $event->recipientId,
                'error'          => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
