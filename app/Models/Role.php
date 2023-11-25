<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsToMany(User::class, UserRole::class,'user_id','role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, RolePermission::class,'role_id','permission_id');
    }
}
