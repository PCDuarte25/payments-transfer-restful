<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Class TransactionAuthorizationService
 *
 * Provides integration with external authorization providers to validate
 * whether a financial transaction is authorized to proceed.
 *
 * @package App\Services
 */
class TransactionAuthorizationService
{
    /**
     * Consults the external authorization service.
     *
     * This method performs a GET request to the authorization endpoint and
     * validates the response structure to confirm approval.
     *
     * @return bool Returns true only if the service responds successfully and explicitly authorizes the request.
     */
    public function authorize(): bool
    {
        $response = Http::get('https://util.devi.tools/api/v2/authorize');

        // Check if the request was successful
        if ($response->failed()) {
            return false;
        }

        $data = $response->json();

        /**
         * Validates the specific structure required for authorization:
         * 1. The root 'status' must be 'success'
         * 2. The 'data.authorization' key must be explicitly true
         */
        return isset($data['status'], $data['data']['authorization']) &&
            $data['status'] === 'success' &&
            $data['data']['authorization'] === true;
    }
}
