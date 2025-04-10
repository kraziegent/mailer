<?php

namespace App\Traits;

use App\Models\MailTemplate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

trait HasMails
{
    public function mails(): MorphMany
    {
        return $this->morphMany(MailTemplate::class, 'mailer');
    }

    public function mailLayout(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) =>
                app(CssToInlineStyles::class)->convert(
                    $value ?? file_get_contents(resource_path('html/emails/layout.html')),
                    file_get_contents(public_path('css/email/default.css'))
                )
        );
    }
}
