<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'super_admin',
            // 'admin',
            // 'operator',
            // 'help_desk',
            // 'outbound',
        ];
        foreach ($data as $key => $value) {
            Role::create([
                'name'=>$value
            ]);
        }
    }
}
