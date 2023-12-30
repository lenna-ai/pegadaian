<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutBound extends Model
{
    use HasFactory;
    protected $fillable = [
        'agent_id',
        'name',
        'call_time',
        'call_duration',
        'status',
        'owned',
    ];

    protected $cast = [
        'call_time' => 'timestamp'
    ];

    protected $table = 'outbound';
}
