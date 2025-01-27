<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionsSeeder::class,
            SupportedBankSeeder::class,
            UserSeeder::class,
            WalletSedder::class,
            BannerSeeder::class,
            CategorySeeder::class,
            CourseSeeder::class,
            InvoiceSeeder::class,
            WithdrawalsRequestSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
