<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\MailgunWebhook;

class Mailgun extends Controller
{
    public function handle()
    {
        $payload = request()->all();

        // Process the payload as needed
        // For example, you can log it or save it to the database
        // smee -u https://smee.io/Ra6h7PpWqimRqyiq -t http://127.0.0.1:8000/webhooks/mailgun

        if (!isset($payload['signature'])) {
            return response()->json(['status' => 'error', 'message' => 'Missing signature'], 400);
        }

        if (static::verifySignature($payload['signature']) === false) {
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        MailgunWebhook::dispatch($payload);

        return response()->json(['status' => 'success']);
    }

    protected static function verifySignature(array $signature): bool
    {
        $hexdigest = hash_hmac('sha256', $signature['timestamp'] . $signature['token'], config('services.mailgun.webhook.secret'), false);

        if (hash_equals($hexdigest, $signature['signature'])) {
            return true;
        }

        return false;
    }
}
