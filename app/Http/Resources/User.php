<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

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
            'name'=>$this->name,
            'email'=>$this->email,
            'password'=>$this->password,
            'access_token' => $this->whenNotNull($this->token),
            'token_type' => $this->when(isset($this->token), function () {
                return 'bearer';
            }),
            'expires_in' => $this->when(isset($this->token), function () {
                return auth()->factory()->getTTL() * 60;
            })
        ];
    }
}
