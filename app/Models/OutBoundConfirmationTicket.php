<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutBoundConfirmationTicket extends Model
{
    use HasFactory;
    protected $fillable = [
        'agent_id',
        'name_agent',
        'ticket_number',
        'category',
        'status',
        'call_time',
        'call_duration',
        'result_call',
    ];

    protected $cast = [
        'call_time' => 'timestamp'
    ];

    protected $table = 'outbound_confirmation_ticket';
}
