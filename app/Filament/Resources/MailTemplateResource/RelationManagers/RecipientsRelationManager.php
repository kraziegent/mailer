<?php

namespace App\Filament\Resources\MailTemplateResource\RelationManagers;

use App\Filament\Imports\RecipientImporter;
use App\Jobs\BulkMailing;
use App\Mail\Template;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class RecipientsRelationManager extends RelationManager
{
    protected static string $relationship = 'recipients';

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['options'] = ['message_id' => Str::uuid()];

        return $data;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('attribute_1')->maxLength(255),
                Forms\Components\TextInput::make('attribute_2')->maxLength(255),
                Forms\Components\TextInput::make('attribute_3')->maxLength(255),
                Forms\Components\TextInput::make('attribute_4')->maxLength(255),
                Forms\Components\TextInput::make('attribute_5')->maxLength(255),
                Forms\Components\TextInput::make('attribute_6')->maxLength(255),
                Forms\Components\TextInput::make('attribute_7')->maxLength(255),
                Forms\Components\TextInput::make('attribute_8')->maxLength(255),
                Forms\Components\TextInput::make('attribute_9')->maxLength(255),
                Forms\Components\TextInput::make('attribute_10')->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->heading(function ()  {
            //     $total = $this->getAllTableRecordsCount();
            //     $registered = $this->getTable()->getQuery()->whereNotNull('registered_at')->count();

            //     return "Attendees (Confirmed {$registered}/{$total})";
            // })
            ->recordTitleAttribute('email')
            ->columns([
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('delivered_at')->label('Delivered')->dateTime('Y-m-d H:i:s'),
                Tables\Columns\TextColumn::make('attribute_1')->label('Attribute 1'),
                Tables\Columns\TextColumn::make('attribute_2')->label('Attribute 2'),
                Tables\Columns\TextColumn::make('attribute_3')->label('Attribute 3'),
                Tables\Columns\TextColumn::make('attribute_4')->label('Attribute 4'),
                Tables\Columns\TextColumn::make('attribute_5')->label('Attribute 5'),
                Tables\Columns\TextColumn::make('attribute_6')->label('Attribute 6'),
                Tables\Columns\TextColumn::make('attribute_7')->label('Attribute 7'),
                Tables\Columns\TextColumn::make('attribute_8')->label('Attribute 8'),
                Tables\Columns\TextColumn::make('attribute_9')->label('Attribute 9'),
                Tables\Columns\TextColumn::make('attribute_10')->label('Attribute 10'),
            ])
            // ->filters([
            //     Filter::make('registered_at')->label('Registered')
            //     ->query(fn (Builder $query) => $query->whereNotNull('registered_at')),
            // ])
            ->headerActions([
                Tables\Actions\Action::make('send_all_mail')
                ->label('Send Mail')
                ->requiresConfirmation()
                ->modalDescription('Only recipients for whom the mail hasnt been delivered will receive this email.')
                ->action(fn() => BulkMailing::dispatch($this->getOwnerRecord(), Filament::auth()->user())),
                Tables\Actions\CreateAction::make(),
                Tables\Actions\ImportAction::make()
                ->importer(RecipientImporter::class)
                ->options(['mail_template_id' => $this->getOwnerRecord()->getKey()]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('send_single_mail')
                    ->label('Send Mail')
                    ->icon('heroicon-m-envelope')
                    ->requiresConfirmation()
                    ->modalDescription('You are about to send an email to the recipient.')
                    ->action(fn(Model $record) => Mail::to($record->email)->send(new Template($this->getOwnerRecord(), Filament::auth()->user(), $record)))
                ])->tooltip('Actions')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('send_bulk_mail')
                    ->label('Send Mail')
                    ->requiresConfirmation()
                    ->modalDescription('You are about to send an email to all selected recipient(s).')
                    ->icon('heroicon-m-envelope')
                    ->action(fn (Collection $records) => BulkMailing::dispatch($this->getOwnerRecord(), Filament::auth()->user(), $records))
                    ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->queryStringIdentifier('recipients');
    }
}
