<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()
            ->create([
            'name' => 'admin',
            'email' => 'f.admin@mail.ru',
            'password' => Hash::make('12345'),
        ]);
        $user->givePermissionTo(['admin']);
    }
}
