<?php

namespace App\Mail;

use DateTime;
use App\Models\Recipient;
use App\Models\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;
use Illuminate\Queue\Middleware\ThrottlesExceptions;

class Template extends TemplateMailable implements ShouldQueue, ShouldBeUnique
{
    use Queueable, SerializesModels;

    public ?string $attribute_1 = null;
    public ?string $attribute_2 = null;
    public ?string $attribute_3 = null;
    public ?string $attribute_4 = null;
    public ?string $attribute_5 = null;
    public ?string $attribute_6 = null;
    public ?string $attribute_7 = null;
    public ?string $attribute_8 = null;
    public ?string $attribute_9 = null;
    public ?string $attribute_10 = null;
    public ?string $mail_banner;
    public ?string $footer;
    public ?string $logo = "https://laravel.com/img/notification-logo.png";

    /**
     * Create a new message instance.
     */
    public function __construct(
        MailTemplate $template,
        protected Model $mailee,
        protected Recipient $recipient,
        protected bool $unsubscribable = false
    )
    {
        $this->mailTemplate = $template;

        $this->attribute_1 = $recipient->attribute_1;
        $this->attribute_2 = $recipient->attribute_2;
        $this->attribute_3 = $recipient->attribute_3;
        $this->attribute_4 = $recipient->attribute_4;
        $this->attribute_5 = $recipient->attribute_5;
        $this->attribute_6 = $recipient->attribute_6;
        $this->attribute_7 = $recipient->attribute_7;
        $this->attribute_8 = $recipient->attribute_8;
        $this->attribute_9 = $recipient->attribute_9;
        $this->attribute_10 = $recipient->attribute_10;

        $this->mail_banner = url("imgs/secmailbanner.png");
        $this->logo = url("imgs/SECLogo.png");
        $this->footer = date('Y') .' '. config('app.name') .' '. __('All rights reserved.');
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [(new ThrottlesExceptions(2, 5))->backoff(10)];
    }

    /**
     * Determine the time at which the job should timeout.
     */
    // public function retryUntil(): DateTime
    // {
    //     return now()->addMinutes(70);
    // }

    /**
     * Get the message headers.
     */
    public function headers(): Headers
    {
        return new Headers(
            messageId: $this->recipient->options['message_id'],
            text: [
                // 'List-Unsubscribe' => route('attendees.unsubscribe', ['attendee' => $this->attendee]),
                // 'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
                'X-Mailgun-Variables' => ['message_id' => $this->recipient->options['message_id']],
            ],
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->mailTemplate->from_address, $this->mailTemplate->from_name ?? $this->mailTemplate->name),
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function getMaileeId(): int
    {
        return $this->mailee->id;
    }

    public function getMaileeMorphClass(): string
    {
        return $this->mailee->getMorphClass();
    }

    public function getHtmlLayout(): string
    {
        return $this->mailee->mail_layout;
    }
}
