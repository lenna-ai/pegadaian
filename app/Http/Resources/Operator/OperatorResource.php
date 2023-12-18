<?php

namespace App\Http\Resources\Operator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name_agent' => $this->name_agent,
            'name_customer' => $this->name_customer,
            'date_to_call' => $this->date_to_call,
            'call_duration' => $this->call_duration,
            'result_call' => $this->result_call,
            'status' => $this->whenNotNull($this->status),
        ];
    }
}
