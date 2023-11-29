<?php

namespace App\Http\Resources;

use App\Http\Resources\RolePermissions\RolePermission;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Log;

class User extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'roles'=>RolePermission::collection($this->Role),
            'access_token' => $this->whenNotNull($this->token),
            'status' => $this->whenNotNull($this->status),
            'login_at' => $this->login_at,
            'logout_at' => $this->logout_at,
            'phone_number' => $this->phone_number,
            'notes' => $this->notes,
            'token_type' => $this->when(isset($this->token), function () {
                return 'bearer';
            }),
            'expires_in' => $this->when(isset($this->token), function () {
                return auth()->factory()->getTTL() * 60;
            })
        ];
    }
}
