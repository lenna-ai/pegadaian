<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InboundMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $dataMessage;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->dataMessage = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info($this->dataMessage);
    }
}
