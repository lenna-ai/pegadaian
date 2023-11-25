<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = User::all();

        foreach ($data as $key => $value) {
            UserRole::create([
                'user_id'=>$value->id,
                'role_id'=>Role::inRandomOrder()->first()->id
            ]);
        }
    }
}
