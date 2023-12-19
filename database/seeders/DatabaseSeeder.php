<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // UserSeeder::class,
            // RoleSeeder::class,
            // PermissionSeeder::class,
            // RolePermissionSeeder::class,
            // UserRoleSeeder::class,
            // CategorySeeder::class,
            // TagSeeder::class,
            StatusTrackSeeder::class
        ]);
    }
}
