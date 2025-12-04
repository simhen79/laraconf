<?php

namespace App\Filament\App\Resources\Speakers;

use App\Filament\App\Resources\Speakers\Pages\CreateSpeaker;
use App\Filament\App\Resources\Speakers\Pages\EditSpeaker;
use App\Filament\App\Resources\Speakers\Pages\ListSpeakers;
use App\Filament\App\Resources\Speakers\Schemas\SpeakerForm;
use App\Filament\App\Resources\Speakers\Tables\SpeakersTable;
use App\Models\Speaker;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Speakers';

    public static function form(Schema $schema): Schema
    {
        return SpeakerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpeakersTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->color('primary'),
                TextEntry::make('email')
            ])
            ->columns(2);
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
            'index' => ListSpeakers::route('/'),
        ];
    }
}
