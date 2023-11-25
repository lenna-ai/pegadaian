<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user): bool
    {
        $result = false;
        foreach ($user->Role as $key => $value) {
            foreach ($value->permissions as $item) {
                if ($item->name == 'create_user') {
                    $result = true;
                }
            }
        }
        return $result;
    }

    public function update(User $user): bool
    {
        $result = false;
        foreach ($user->Role as $key => $value) {
            foreach ($value->permissions as $item) {
                if ($item->name == 'update_user') {
                    $result = true;
                }
            }
        }
        return $result;
    }

    public function delete(User $user): bool
    {
        $result = false;
        foreach ($user->Role as $key => $value) {
            foreach ($value->permissions as $item) {
                if ($item->name == 'delete_user') {
                    $result = true;
                }
            }
        }
        return $result;
    }

    public function read(User $user): bool
    {
        $result = false;
        foreach ($user->Role as $key => $value) {
            foreach ($value->permissions as $item) {
                if ($item->name == 'read_user') {
                    $result = true;
                }
            }
        }
        return $result;
    }
}
