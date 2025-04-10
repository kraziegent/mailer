<?php

namespace App\Filament\Resources\MailTemplateResource\RelationManagers;

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
                Tables\Actions\CreateAction::make()
                // ->using(function (array $data, $livewire): Model {
                //     $service = app();
                //     $data['event_id'] = $livewire->ownerRecord->getKey();

                //     return $service->save($data);
                // }),

            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                ])->tooltip('Actions')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Send Mail')
                    ->requiresConfirmation()
                    ->icon('heroicon-m-qr-code')
                    ->action(fn (Collection $records) => $records->each(function($record) {

                    }))
                    ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->queryStringIdentifier('recipients');
    }
}
