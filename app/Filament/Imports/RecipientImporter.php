<?php

namespace App\Filament\Imports;

use App\Models\Recipient;
use Illuminate\Support\Str;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class RecipientImporter extends Importer
{
    protected static ?string $model = Recipient::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('email')->requiredMapping()->rules(['required', 'max:255']),
            ImportColumn::make('attribute_1'),
            ImportColumn::make('attribute_2'),
            ImportColumn::make('attribute_3'),
            ImportColumn::make('attribute_4'),
            ImportColumn::make('attribute_5'),
            ImportColumn::make('attribute_6'),
            ImportColumn::make('attribute_7'),
            ImportColumn::make('attribute_8'),
            ImportColumn::make('attribute_9'),
            ImportColumn::make('attribute_10'),
        ];
    }

    public function resolveRecord(): ?Recipient
    {
        $recipient = Recipient::query()->firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'email' => $this->data['email'],
            'mail_template_id' => $this->options['mail_template_id'],
        ]);

        $recipient->template()->associate($this->options['mail_template_id']);
        $recipient->options = array_merge($recipient->options ?? [], ['message_id' => Str::uuid()]);

        return $recipient;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your recipient import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
