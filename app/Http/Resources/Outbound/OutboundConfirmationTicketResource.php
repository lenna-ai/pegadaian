<?php

namespace App\Http\Resources\Outbound;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OutboundConfirmationTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_agent' => $this->name_agent,
            'ticket_number' => $this->ticket_number,
            'category' => $this->category,
            'status' => $this->status,
            'call_time' => $this->call_time,
            'call_duration' => $this->call_duration,
            'result_call' => $this->result_call,
        ];
    }
}
