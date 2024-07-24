<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequests\AuthLoginRequest;
use App\Http\Requests\AuthRequests\AuthRegisterRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request): JsonResponse
    {
        try {
            $loginId = $this->generateUniqueLoginId();
            $user = User::query()
                ->create([
                    'name' => $request->name,
                    'email' => $request->email ?? $loginId . '@mail.ru',
                    'password' => Hash::make($request->password),
                    'login_id' => $loginId,
                ]);

            return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['message' => 'error'], 500);
        }
    }

    /**
     * @throws Exception
     */
    private function generateUniqueLoginId(): int
    {
        do {
            $loginId = random_int(10000000, 99999999);
        } while (
            User::query()
                ->where('login_id', $loginId)
                ->exists()
        );

        return $loginId;
    }

    public function login(AuthLoginRequest $request): JsonResponse
    {
        //
        $login_id = $request->input('login_id');
        $password = $request->input('password');

        if (Auth::attempt(['login_id' => $login_id, 'password' => $password])) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token, 'user' => ['name' => $user->name]]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);

        //
//        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
//            $user = Auth::user();
//            $token = $user->createToken('auth_token')->plainTextToken;
//
//            return response()->json(['token' => $token, 'user' => ['name' => $user->name]]);
//        } else {
//            return response()->json(['error' => 'Unauthorized'], 401);
//        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
