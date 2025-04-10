<?php

namespace App\Jobs;

use App\Models\Recipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MailgunWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected array $payload)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // {
        //     “signature”:
        //     {
        //       "timestamp": "1529006854",
        //       "token": "a8ce0edb2dd8301dee6c2405235584e45aa91d1e9f979f3de0",
        //       "signature": "d2271d12299f6592d9d44cd9d250f0704e4674c30d79d07c47a66f95ce71cf55"
        //     }
        //     “event-data”:
        //     {
        //       "event": "opened",
        //       "timestamp": 1529006854.329574,
        //       "id": "DACSsAdVSeGpLid7TN03WA",
        //       "user-variables": {
        //         "message_id": "123"
        //        },
        //       // ...
        //     }
        // }

        Log::info('Mailgun Webhook', [
            'payload' => $this->payload,
        ]);

        $receipient = rescue(fn() => Recipient::query()->where('options->message_id', $this->payload['event-data']['user-variables']['message_id'])->first(), report: false);

        if ($receipient) {
            if ($this->payload['event-data']['event'] == 'delivered') {
                $receipient->delivered_at = $this->payload['event-data']['timestamp'];
            }

            $receipient->options = array_merge($receipient->options, ['mailgun' => $this->payload]);

            $receipient->save();
        }
    }
}
