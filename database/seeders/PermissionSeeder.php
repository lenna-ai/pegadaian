<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['create_user','update_user','read_user','delete_user'];
        foreach ($data as $key => $value) {
            Permission::create([
                'name'=> $value
            ]);
        }
    }
}
