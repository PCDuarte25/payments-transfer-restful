<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransactionNotificationService
{
    public function notify(int $userId, int $amount): void
    {
        // Aqui eu usaria o $userId e o $amount para pegar as infos do usuário
        // e mandar no e-mail com algum serviço externo como mailHog por exemplo.
        $response = Http::post('https://util.devi.tools/api/v1/notify');

        if ($response->failed()) {
            Log::warning('Falha ao enviar notificação', [
                'service' => 'transaction_notification',
                'response' => $response->body(),
            ]);

            return;
        }
        else {
            Log::warning('Sucesso ao enviar notificação', [
                'service' => 'transaction_notification',
                'response' => $response->body(),
            ]);

            return;
        }
    }
}
