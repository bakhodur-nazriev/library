<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{

    public function get(): LengthAwarePaginator|JsonResponse
    {
        try {
            return User::query()
                ->with(['roles', 'permissions'])
                ->paginate(10);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function store(array $attributes): JsonResponse
    {
        $user = User::query()
            ->create([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'password' => Hash::make($attributes['password']),
            ]);

        return response()->json(['user' => $user], 201);
    }

    public function update(array $attributes, $id): JsonResponse
    {
        $user = User::query()
            ->findOrFail($id);

        $user->name = $attributes['name'];
        $user->email = $attributes['email'];

        if (isset($attributes['password'])) {
            $user->password = Hash::make($attributes['password']);
        }

        $user->save();

        return response()->json(['user' => $user]);
    }
}
