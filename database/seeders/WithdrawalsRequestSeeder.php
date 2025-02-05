<?php

namespace Database\Seeders;

use App\Models\SupportedBank;
use App\Models\Wallet;
use App\Models\WithdrawalRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WithdrawalsRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wallets = Wallet::query()->pluck('id')->toArray();
        $countwallets = sizeof($wallets);
        $supportBank = SupportedBank::query()->pluck('name')->all();

        for ($i = 1; $i <= 100; $i++) {
            $requestDate = fake()->dateTimeBetween('-1 year', 'now');

            WithdrawalRequest::create([
                'wallet_id' => $wallets[$i % $countwallets],
                'amount' => rand(10000, 99999999),
                'bank_name' => fake()->randomElement($supportBank),
                'account_number' => fake()->bankAccountNumber(),
                'account_holder' => fake()->name(),
                'note' => fake()->sentence(),
                'qr_code' => fake()->uuid(),
                'status' => fake()->randomElement(['pending', 'completed', 'failed']),
                'request_date' => $requestDate,
                'completed_date' => fake()->dateTimeBetween($requestDate, 'now + 1 day')
            ]);
        }
    }
}
