<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->whereHas('roles', function($query){
            $query->where('name', 'instructor');
        })->pluck('id')->toArray();

        foreach ($users as $user) {
            Wallet::create([
                'user_id' => $user,
                'balance' => rand(10000,99999999),
                'status' => fake()->randomElement([0, 1])
            ]);
        }
    }
}
