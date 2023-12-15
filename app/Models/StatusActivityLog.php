<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusActivityLog extends Model
{
    use HasFactory;

    protected $table = 'status_activity_log';
    protected $guarded = [];
}
