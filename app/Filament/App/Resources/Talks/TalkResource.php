<?php

namespace App\Filament\App\Resources\Talks;

use App\Filament\App\Resources\Talks\Pages\CreateTalk;
use App\Filament\App\Resources\Talks\Pages\EditTalk;
use App\Filament\App\Resources\Talks\Pages\ListTalks;
use App\Filament\App\Resources\Talks\Schemas\TalkForm;
use App\Filament\App\Resources\Talks\Tables\TalksTable;
use App\Models\Talk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Talks';

    public static function form(Schema $schema): Schema
    {
        return TalkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TalksTable::configure($table);
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
            'index' => ListTalks::route('/'),
            'create' => CreateTalk::route('/create'),
            'edit' => EditTalk::route('/{record}/edit'),
        ];
    }
}
