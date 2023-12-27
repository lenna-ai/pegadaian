<?php

namespace App\Http\Helpers;

use Illuminate\Database\Eloquent\Collection;

trait CountingData
{
    protected function countDurationStatus(Collection $model):int {
        $duration = 0;

        foreach ($model as $key => $value) {
            $duration += $value->duration;
        }

        return $duration;
    }
}
