<?php

namespace App\Policies;

use App\Models\HelpDesk;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HelpDeskPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $result = false;
        foreach ($user->Role as $key => $value) {
            foreach ($value->permissions as $item) {
                if ($item->name == 'create_helpdesk') {
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
                if ($item->name == 'read_helpdesk') {
                    $result = true;
                }
            }
        }
        return $result;
    }
}
