<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Mail\Template;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MailTemplate;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use App\Filament\Resources\MailTemplateResource\Pages;
use App\Filament\Resources\MailTemplateResource\RelationManagers;

class MailTemplateResource extends Resource
{
    protected static ?string $model = MailTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('display_name')->required()->label('Template Name'),
                        Forms\Components\TextInput::make('from_address')->required()->email()->label('From Address'),
                        Forms\Components\TextInput::make('from_name')->label('From Name'),
                        Forms\Components\TextInput::make('subject')->required()->label('Mail Subject')->helperText('You can use a variable here.'),
                        TiptapEditor::make('html_template')->required()->label('HTML Template')->extraInputAttributes(['style' => 'min-height: 35rem;']),
                        Forms\Components\TextArea::make('text_template')->required()->label('Text Template')->rows(25),
                    ]),
                ])
                ->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Variables')
                    ->description('Available replacement variables for the mail template.')
                    ->schema([
                        Forms\Components\Placeholder::make('')
                        ->content(function() {
                            $html = '<ul>';
                                foreach(Template::getVariables() as $variable) {
                                    $html .= "<li>{{ {$variable} }}</li>";
                                }
                            $html .= '</ul>';

                            return new HtmlString($html);
                        })
                    ]),
                ])
                ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        Tables\Columns\TextColumn::make('display_name')
                        ->searchable(),
                    ]),
                    Tables\Columns\TextColumn::make('created_at')
                    ->icon('heroicon-m-calendar-days'),
                ])->from('md')
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RecipientsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailTemplates::route('/'),
            'create' => Pages\CreateMailTemplate::route('/create'),
            'edit' => Pages\EditMailTemplate::route('/{record}/edit'),
        ];
    }
}
