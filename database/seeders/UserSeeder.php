<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {
            $user = User::create([
                'code' => str_replace('-', '', Str::uuid()),
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'avatar' => 'https://res.cloudinary.com/dvrexlsgx/image/upload/v1732148083/Avatar-trang-den_apceuv_pgbce6.png',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            ]);
            $user->assignRole(fake()->randomElement(['member', 'instructor', 'admin']));
        }

        $permissions = ['member', 'instructor', 'admin', 'super_admin'];
        foreach ($permissions as $permission) {
            $email = $permission == 'super_admin' ? 'superadmin': $permission;
            $user = User::create([
                'code' => str_replace('-', '', Str::uuid()),
                'name' => Str::ucfirst($permission),
                'email' => $email . '@gmail.com',
                'email_verified_at' => now(),
                'avatar' => 'https://res.cloudinary.com/dvrexlsgx/image/upload/v1735530492/users/BHw9E6fCJw.webp',
                'password' => password_hash($email . '@gmail.com', PASSWORD_DEFAULT),
            ]);
            $user->assignRole($permission);
        }
    }
}
