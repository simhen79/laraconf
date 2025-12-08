<?php

namespace App\Filament\App\Resources\Speakers;

use App\Enums\TalkStatus;
use App\Filament\App\Resources\Speakers\Pages\CreateSpeaker;
use App\Filament\App\Resources\Speakers\Pages\EditSpeaker;
use App\Filament\App\Resources\Speakers\Pages\ListSpeakers;
use App\Filament\App\Resources\Speakers\Pages\ViewSpeaker;
use App\Filament\App\Resources\Speakers\RelationManagers\TalksRelationManager;
use App\Filament\App\Resources\Speakers\Schemas\SpeakerForm;
use App\Filament\App\Resources\Speakers\Tables\SpeakersTable;
use App\Models\Speaker;
use BackedEnum;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    //protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|null|\UnitEnum $navigationGroup = 'Second Group';

    protected static ?string $recordTitleAttribute = 'name';

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
                Section::make('Personal Information')
                    ->schema([
                        ImageEntry::make('avatar')->imageHeight(100)
                            ->circular(),
                        Group::make()
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('name'),
                                TextEntry::make('email'),
                                TextEntry::make('twitter_handle')
                                    ->label('Twitter')
                                    ->getStateUsing(fn (Speaker $record) => '@'.$record->twitter_handle)
                                    ->url(function (Speaker $record) {
                                        return 'https://x.com/' . $record->twitter_handle;
                                    })
                                    ->openUrlInNewTab(true),
                                TextEntry::make('has_spoken')
                                    ->getStateUsing(function (Speaker $record) {
                                        $count = $record
                                                    ->talks()
                                                    ->where('status', TalkStatus::APPROVED)->count() > 0;
                                        return $count ? 'Previous Speaker' : 'Has Not Spoken';
                                    })
                                    ->label('Has Spoken')
                                    ->badge()
                                    ->color(function ($state) {
                                        if ($state === 'Previous Speaker') return 'success';
                                        return 'primary';
                                    }),
                            ])
                        ->columns(2)
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Other Information')
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('bio')
                            ->html()
                            ->prose(),
                        TextEntry::make('qualifications')
                            ->listWithLineBreaks()
                            ->bulleted()
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TalksRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSpeakers::route('/'),
            'view' => ViewSpeaker::route('/{record}'),
        ];
    }
}
