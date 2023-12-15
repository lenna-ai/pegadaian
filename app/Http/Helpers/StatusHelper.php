<?php

namespace App\Http\Helpers;

use App\Models\StatusActivityLog;
use Carbon\Carbon;

class StatusHelper
{

    public static function changeStatus($user_id, $status) : Void
    {
        $prev = StatusActivityLog::where('user_id', $user_id)
        ->orderBy('id', 'desc')
        ->first();

        if ($prev) {
            $start = Carbon::parse($prev->created_at);
            $end = Carbon::parse(now());
            $prev->duration = $start->diffInMinutes($end); //duration by a minutes
            $prev->change_at = date('Y-m-d H:i:s');
            $prev->save();
        }
        $data = [
            'user_id' => $user_id,
            'status' => $status
        ];
        StatusActivityLog::create($data);


    }

}
