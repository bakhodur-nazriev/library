<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;

class LoginIdUserSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run(): void
    {
        User::query()
            ->get()
            ->each( function ($user) {
                if (!$user->login_id) {
                   $user->login_id = $this->generateUniqueLoginId();
                   $user->save();
                }
            });
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
}
