<?php

namespace Database\Seeders;

use App\Models\Fund;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAndFundsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commonUser = User::create([
            'full_name' => 'Usuário Comum',
            'document' => '12345678900',
            'email' => 'common@example.com',
            'password' => Hash::make('password123'),
            'user_type' => 'common',
        ]);

        Fund::create([
            'user_id' => $commonUser->id,
            'balance' => 500,
        ]);

        $merchantUser = User::create([
            'full_name' => 'Usuário Merchant',
            'document' => '98765432100',
            'email' => 'merchant@example.com',
            'password' => Hash::make('password123'),
            'user_type' => 'merchant',
        ]);

        Fund::create([
            'user_id' => $merchantUser->id,
            'balance' => 500,
        ]);
    }
}
