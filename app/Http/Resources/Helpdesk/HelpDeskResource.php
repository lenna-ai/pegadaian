<?php

namespace App\Http\Resources\Helpdesk;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HelpDeskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'branch_code' => $this->branch_code,
            'branch_name'=> $this->branch_name,
            'branch_name_staff'=> $this->branch_name_staff,
            'branch_phone_number'=> $this->branch_phone_number,
            'date_to_call'=> $this->date_to_call,
            'call_duration'=> $this->call_duration,
            'result_call'=> $this->result_call,
            'name_agent'=> $this->name_agent,
            'input_voice_call'=> $this->input_voice_call,
        ];
    }
}
