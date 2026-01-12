<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\UseCases\UserUseCases\UserUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class UsersController
 * * Handles all HTTP-related operations for user management, including
 * registration, profile updates, and account deletion.
 *
 * @package App\Http\Controllers\Api\V1
 */
class UsersController extends Controller
{
    /**
     * The service orchestrator for user domain logic.
     *
     * @var UserUseCase
     */
    private UserUseCase $userUseCase;

    /**
     * UsersController constructor.
     *
     * @param UserUseCase $userUseCase
     */
    public function __construct(UserUseCase $userUseCase)
    {
        $this->userUseCase = $userUseCase;
    }

    /**
     * Register a new user in the system.
     *
     * @param UserRequest $request Contains validated 'name', 'email', 'document', and 'password'.
     * @return JsonResponse Returns the created user data.
     */
    public function createNewUser(UserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = $this->userUseCase->createUser($data);

            return response()->json($user, 201);
        } catch (Exception $e) {
            $status = $e->getCode() ?: 400;
            return response()->json(['error' => $e->getMessage()], $status);
        }
    }

    /**
     * Update an existing user's information.
     *
     * @param string $userId The unique identifier of the user.
     * @param UserRequest $request Contains validated fields to be updated.
     * @return JsonResponse Returns the updated user data.
     */
    public function updateUser(string $userId, UserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = $this->userUseCase->updateUser($userId, $data);

            return response()->json($user, 200);
        } catch (Exception $e) {
            $status = $e->getCode() ?: 400;
            return response()->json(['error' => $e->getMessage()], $status);
        }
    }

    /**
     * Remove a user and their associated records.
     *
     * @param string $userId The unique identifier of the user.
     * @return JsonResponse
     */
    public function deleteUser(string $userId): JsonResponse
    {
        try {
            $this->userUseCase->deleteUser($userId);

            return response()->json([
                'message' => 'Usuário removido com sucesso'
            ], 200);
        } catch (Exception $e) {
            $status = $e->getCode() ?: 400;
            return response()->json(['error' => $e->getMessage()], $status);
        }
    }
}
