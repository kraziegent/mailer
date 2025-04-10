<?php

namespace App\Jobs;

use App\Mail\Template;
use App\Models\MailTemplate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class BulkMailing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected MailTemplate $template,
        protected User $user
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $minutes = 5;

        $this->template->recipients()->chunk(25, function(Collection $recipients) use($minutes) {
            foreach($recipients as $recipient) {
                Mail::to($recipient->email)->later(now()->addMinutes($minutes), new Template($this->template, $this->user, $recipient));

                $minutes = $minutes + 5;
            }
        });
    }
}
