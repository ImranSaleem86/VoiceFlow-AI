<?php

namespace App\Filament\Resources\Transcription;

use App\Filament\Resources\Transcription\Pages\CreateTranscription;
use App\Filament\Resources\Transcription\Pages\EditTranscription;
use App\Filament\Resources\Transcription\Pages\ListTranscription;
use App\Filament\Resources\Transcription\Pages\ViewTranscription;
use App\Filament\Resources\Transcription\Schemas\TranscriptionForm;
use App\Filament\Resources\Transcription\Tables\TranscriptionTable;
use App\Models\Transcription;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TranscriptionResource extends Resource
{
    protected static ?string $model = Transcription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TranscriptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TranscriptionTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTranscription::route('/'),
            'create' => CreateTranscription::route('/create'),
            'edit' => EditTranscription::route('/{record}/edit'),
        ];
    }
}
