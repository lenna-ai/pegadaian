<?php

namespace App\Http\Resources\RolePermissions;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RolePermission extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'=>$this->name,
            'permissions'=>Permissions::collection($this->permissions)
        ];
    }
}
