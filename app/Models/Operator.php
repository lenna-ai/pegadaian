<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_agent',
        'name_customer',
        'date_to_call',
        'call_duration',
        'result_call',
        'category',
        'input_voice_call',
        'tag',
        'agent_id'
    ];

    protected $cast = [
        'date_to_call' => 'timestamp'
    ];
}
