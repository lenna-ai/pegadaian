<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            //admin
            // [
            //     'role_id' => Role::where('name','admin')->first()->id,
            //     'permission_id' => Permission::where('name','create_user')->first()->id
            // ],
            // [
            //     'role_id' => Role::where('name','admin')->first()->id,
            //     'permission_id' => Permission::where('name','update_user')->first()->id
            // ],
            // [
            //     'role_id' => Role::where('name','admin')->first()->id,
            //     'permission_id' => Permission::where('name','read_user')->first()->id
            // ],
            // [
            //     'role_id' => Role::where('name','admin')->first()->id,
            //     'permission_id' => Permission::where('name','delete_user')->first()->id
            // ],

            // //operator
            // [
            //     'role_id' => Role::where('name','operator')->first()->id,
            //     'permission_id' => Permission::where('name','create_operator')->first()->id
            // ],
            // [
            //     'role_id' => Role::where('name','operator')->first()->id,
            //     'permission_id' => Permission::where('name','read_operator')->first()->id
            // ],
            // [
            //     'role_id' => Role::where('name','operator')->first()->id,
            //     'permission_id' => Permission::where('name','update_operator')->first()->id
            // ],
            // //helpdesk
            // [
            //     'role_id' => Role::where('name','help_desk')->first()->id,
            //     'permission_id' => Permission::where('name','create_helpdesk')->first()->id
            // ],
            // [
            //     'role_id' => Role::where('name','help_desk')->first()->id,
            //     'permission_id' => Permission::where('name','read_helpdesk')->first()->id
            // ],
            // [
            //     'role_id' => Role::where('name','help_desk')->first()->id,
            //     'permission_id' => Permission::where('name','update_helpdesk')->first()->id
            // ],
            //outbound
            [
                'role_id' => Role::where('name','outbound')->first()->id,
                'permission_id' => Permission::where('name','create_outbound')->first()->id
            ],
            [
                'role_id' => Role::where('name','outbound')->first()->id,
                'permission_id' => Permission::where('name','read_outbound')->first()->id
            ],
            [
                'role_id' => Role::where('name','outbound')->first()->id,
                'permission_id' => Permission::where('name','update_outbound')->first()->id
            ],
        ];

        foreach ($data as $key => $value) {
            RolePermission::create($value);
        }
    }
}
