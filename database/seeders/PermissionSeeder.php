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
        $data = [
            //user
            'create_user',
            'update_user',
            'read_user',
            'delete_user',

            //operator
            'insert_operator',

            //helpdesk
            'insert_helpdesk'
        ];
        foreach ($data as $key => $value) {
            Permission::create([
                'name'=> $value
            ]);
        }
    }
}
