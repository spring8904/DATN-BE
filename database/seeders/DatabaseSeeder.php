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
        $startTime = microtime(true);

        $this->call([
            RoleSeeder::class,
            PermissionsSeeder::class,
            SupportedBankSeeder::class,
            UserSeeder::class,
            WalletSedder::class,
            BannerSeeder::class,
            CategorySeeder::class,
            CourseSeeder::class,
            CouponSeeder::class,
            PostSeeder::class,
            InvoiceSeeder::class,
            WithdrawalsRequestSeeder::class,
            TransactionSeeder::class,
        ]);

        $endTime = microtime(true);

        echo 'Thời gian thực hiện: '. round($endTime - $startTime) . 's';
    }
}
