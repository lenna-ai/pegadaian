<?php

namespace App\Policies;

use App\Models\User;

class OperatorPolicy
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
                if ($item->name == 'create_operator') {
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
                if ($item->name == 'read_operator') {
                    $result = true;
                }
            }
        }
        return $result;
    }
}
