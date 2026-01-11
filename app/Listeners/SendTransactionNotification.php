<?php

namespace App\Listeners;

use App\Events\TransactionCompleted;
use App\Services\TransactionNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendTransactionNotification implements ShouldQueue
{
    public $tries = 3;
    public $backoff = 10;

    private $notificationService;
    /**
     * Create the event listener.
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
        $this->notificationService->notify(
            $event->recipientId,
            $event->amount
        );
    }
}
