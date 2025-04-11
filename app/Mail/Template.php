<?php

namespace App\Mail;

use App\Models\Recipient;
use App\Models\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class Template extends TemplateMailable implements ShouldQueue
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

        // Use line below to generate a base64 image string or just add a url for your logo
        // $img = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path("imgs/SECLogo.png")));
        $logo = file_get_contents(public_path("imgs/logo"));

        $this->setAdditionalData([
            'header' => "<img src=$logo alt='Logo' style='width: auto; height: 75px; max-height: 75px;'/>",
            'title' => $template->subject,
            'footer' => date('Y') .' '. config('app.name') .' '. __('All rights reserved.'),
        ]);
    }

    /**
     * Get the message headers.
     */
    public function headers(): Headers
    {
        return new Headers(
            text: [
                // 'List-Unsubscribe' => route('attendees.unsubscribe', ['attendee' => $this->attendee]),
                // 'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
                'X-Mailgun-Variables' => json_encode(['message_id' => $this->recipient->options['message_id'] ?? null]),
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
