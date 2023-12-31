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
            'id' => $this->id,
            'name_agent' => $this->name_agent,
            'name_customer' => $this->name_customer,
            'date_to_call' => $this->date_to_call,
            'call_duration' => $this->call_duration,
            'result_call' => $this->result_call,
            'category' => $this->category,
            'tag' => $this->tag,
            'input_voice_call'=> !is_null($this->input_voice_call) ? env('APP_URL','https://pegadaian-api.lenna.ai').Storage::disk('local')->url($this->input_voice_call) : null,
        ];
    }
}
