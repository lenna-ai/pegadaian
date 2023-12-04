<?php

namespace App\Http\Helpers;

use App\Models\StatusActivityLog;

class StatusHelper
{

    public static function changeStatus($user_id, $status) : Void
    {
        $prev = StatusActivityLog::where('user_id', $user_id)
        ->orderBy('id', 'desc')
        ->first();

        if ($prev) {
            $prev->duration = round(abs(time() - strtotime($prev->created_at)) / 60,2);
            $prev->change_at = date('Y-m-d H:i:s');
            $prev->save();
        }

        StatusActivityLog::insert([
            'user_id' => $user_id,
            'status' => $status
        ]);


    }

}