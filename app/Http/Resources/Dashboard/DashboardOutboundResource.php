<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardOutboundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'count_outbound' => $this->whenNotNull(isset($this->resource->count_outbound) ?  $this->resource->count_outbound : null),
            'average_call_time' => $this->whenNotNull(isset($this->resource->average_call_time) ? $this->resource->average_call_time : null),
            'performance_hourly_today' => $this->whenNotNull(isset($this->resource->items) ? $this->resource->items : null),
        ];
    }
}
