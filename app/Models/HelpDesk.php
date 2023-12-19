<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpDesk extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_code',
        'branch_name',
        'branch_name_staff',
        'branch_phone_number',
        'date_to_call',
        'call_duration',
        'result_call',
        'name_agent',
        'input_voice_call',
        'agent_id',
        'status',
        'category',
        'tag',
        'parent_branch'
    ];

    protected $cast = [
        'date_to_call' => 'timestamp'
    ];
}
