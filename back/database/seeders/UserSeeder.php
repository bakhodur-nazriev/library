<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        //
        $user = User::query()
            ->create([
            'name' => 'admin',
            'email' => 'f.admin@mail.ru',
            'password' => Hash::make('12345678'),
        ]);
        $user->givePermissionTo(['admin']);

        //
        $user = User::query()
            ->create([
                'name' => 'shawqat',
                'email' => 'sh.admin@mail.ru',
                'password' => Hash::make('12345678'),
            ]);
        $user->givePermissionTo(['admin']);

    }
}
