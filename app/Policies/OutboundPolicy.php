<?php

namespace App\Policies;

use App\Models\User;

class OutboundPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public function create(User $user): bool
    {
        $result = false;
        foreach ($user->Role as $key => $value) {
            foreach ($value->permissions as $item) {
                if ($item->name == 'create_outbound') {
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
                if ($item->name == 'read_outbound') {
                    $result = true;
                }
            }
        }
        return $result;
    }
}
