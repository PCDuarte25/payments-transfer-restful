<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TransactionAuthorizationService
{
    public function authorize(): bool
    {
        $response = Http::get('https://util.devi.tools/api/v2/authorize');

        return $response->successful() &&
            $response->json()['status'] === 'success' &&
            $response->json()['data']['authorization'] === TRUE;
    }
}
