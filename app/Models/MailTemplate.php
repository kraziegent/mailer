<?php

namespace App\Models;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MailTemplates\Interfaces\MailTemplateInterface;
use Spatie\MailTemplates\Models\MailTemplate as Template;

class MailTemplate extends Template implements MailTemplateInterface
{
    public function mailer(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeForMailable(Builder $query, Mailable $mailable): Builder
    {
        return $query
            ->where('mailable', get_class($mailable))
            ->where('mailer_id', $mailable->getMaileeId())
            ->where('mailer_type', $mailable->getMaileeMorphClass());
    }

    public function recipients()
    {
        return $this->hasMany(Recipient::class);
    }
}
