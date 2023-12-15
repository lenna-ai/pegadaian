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
            'read_operator',
            'create_operator',
            'update_operator',
            'delete_operator',

            //helpdesk
            'read_helpdesk',
            'create_helpdesk',
            'update_helpdesk',
            'delete_helpdesk',
        ];
        foreach ($data as $key => $value) {
            Permission::create([
                'name'=> $value
            ]);
        }
    }
}
