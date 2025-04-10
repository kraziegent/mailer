<?php

namespace App\Filament\Resources\MailTemplateResource\Pages;

use App\Filament\Resources\MailTemplateResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use Filament\Facades\Filament;
use Illuminate\Support\Str;

class CreateMailTemplate extends CreateRecord
{
    protected static string $resource = MailTemplateResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['name'] = Str::slug($data['display_name']);
        $data['mailable'] = 'App\Mail\Template';
        $data['mailer_type'] = 'user';
        $data['mailer_id'] = Filament::auth()->id();

        return $data;
    }
}
