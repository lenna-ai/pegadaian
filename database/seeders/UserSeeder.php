<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'super_admin',
            'email' => 'super_admin@lenna.ai',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'remember_token' => str::random(10),
            'notes' => str::random(10),
            'phone_number' => str::random(10),
        ]);

        // User::create([
        //     'name'=>'admin',
        //     'email' => 'admin@lenna.ai',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('secret'),
        //     'remember_token' => str::random(10),
        //     'notes' => str::random(10),
        //     'phone_number' => str::random(10),
        // ]);

        // User::create([
        //     'name'=>'operator',
        //     'email' => 'operator@lenna.ai',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('secret'),
        //     'remember_token' => str::random(10),
        //     'notes' => str::random(10),
        //     'phone_number' => str::random(10),
        // ]);

        // User::create([
        //     'name'=>'helpdesk',
        //     'email' => 'helpdesk@lenna.ai',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('secret'),
        //     'remember_token' => str::random(10),
        //     'notes' => str::random(10),
        //     'phone_number' => str::random(10),
        // ]);

        // User::create([
        //     'name'=>'outbound',
        //     'email' => 'outbound@lenna.ai',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('secret'),
        //     'remember_token' => str::random(10),
        //     'notes' => str::random(10),
        //     'phone_number' => str::random(10),
        // ]);
    }
}
