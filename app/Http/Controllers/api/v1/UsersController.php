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
        $data = $request->validated();

        $user = $this->userUseCase->createUser($data);

        $data = [
            'teste' => 'teste',
        ];
        return response()->json($data, 200);
    }
}
