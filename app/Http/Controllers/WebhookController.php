<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function checkout_success(Request $request) {
        Log::info(json_encode($request->all()));
    }
}
