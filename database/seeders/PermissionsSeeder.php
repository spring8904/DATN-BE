<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'user' =>  [
                'read',
                'create',
                'update',
                'delete'
            ],
            'role' =>  [
                'read',
                'create',
                'update',
                'delete'
            ],
            'permission' =>  [
                'read',
                'create',
                'update',
                'delete'
            ],
            'category' =>  [
                'read',
                'create',
                'update',
                'delete'
            ],
            'banners' => [
                'read',
                'create',
                'update',
                'delete'
            ],
            'coupon' => [
                'read',
                'create',
                'update',
                'delete'
            ],
            'post' => [
                'read',
                'create',
                'update',
                'delete'
            ],
            'course' => [
                'approve',
                'read',
                'create',
                'update',
            ],
            'chapter' => [
                'read',
                'create',
                'update',
                'delete'
            ],
            'lesson' => [
                'read',
                'create',
                'update',
                'delete'
            ],
            'transaction' => [
                'read',
                'update',
            ],
            'setting' => [
                'read',
                'update',
            ],
            'revenue' => [
                'read',
            ],
            'view' => [
                'dashboard'

            ]
        ];

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                Permission::create(['name' => $module . '.' . $action]);
            }
        }
    }
}
