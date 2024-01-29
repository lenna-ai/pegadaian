<?php

namespace App\Http\Controllers\API\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\InboundMessage;
use Illuminate\Http\Request;

class WebhookMailController extends Controller
{
    public function received(Request $request)
    {
        InboundMessage::dispatch($request->all())->onQueue('webhook');
    }
}
