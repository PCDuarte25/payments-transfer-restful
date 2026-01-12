<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthController
 *
 * Manages the user authentication lifecycle, including issuing
 * and revoking access tokens for the API.
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * Authenticate a user and return an access token.
     *
     * This method validates the credentials against the database.
     * If successful, it generates a new API token for the user.
     *
     * @param LoginRequest $request Validated email and password.
     * @return JsonResponse Returns the token and user profile on success, or 401 on failure.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->validated();

        if (Auth::attempt($request->only('email', 'password'))) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Generates a personal access token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user'         => $user
            ], 200);
        }

        return response()->json([
            'error' => 'Credenciais inválidas.'
        ], 401);
    }

    /**
     * Terminate the current user session.
     *
     * Revokes the access token used for the current request,
     * effectively logging the user out of this specific device/session.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        // Delete the token currently in use
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Deslogado com sucesso.'
        ], 200);
    }
}
