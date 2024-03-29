<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequests\UserStoreRequest;
use App\Http\Requests\UserRequests\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(private readonly UserService $userService){}

    public function index(Request $request): LengthAwarePaginator|JsonResponse
    {
        return $this->userService->get($request->all());
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        return $this->userService->store($request->all());
    }

    public function update(UserUpdateRequest $request, $id): JsonResponse
    {
        return $this->userService->update($request->all(), $id);
    }

    public function destroy($id): JsonResponse
    {
        User::query()
            ->findOrFail($id)
            ->delete();

        return response()->json(null, 204);
    }

}
