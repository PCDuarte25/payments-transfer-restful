<?php

namespace App\Http\Controllers\api\v1;

use App\Application\UseCases\UserUseCases\UserUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;

class UsersController extends Controller
{
    private UserUseCase $userUseCase;

    public function __construct(UserUseCase $userUseCase)
    {
        $this->userUseCase = $userUseCase;
    }

    public function createNewUser(UserRequest $request)
    {
        try {
            $data = $request->validated();

            $user = $this->userUseCase->createUser($data);

            return response()->json($user, 200);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateUser(string $id, UserRequest $request)
    {
        try {
            $data = $request->validated();

            $user = $this->userUseCase->updateUser($id, $data);

            return response()->json($user, 200);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteUser(string $id)
    {
        try {
            $this->userUseCase->deleteUser($id);

            return response()->json(['message' => 'User deleted successfully'], 200);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
