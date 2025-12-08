<?php

namespace App\Filament\App\Resources\Talks\Tables;

use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use App\Models\Talk;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TalksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action) {
                return $action->button()->label('Filters');
            })
            ->striped()
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->description(function (Talk $record) {
                        return Str::of($record->abstract)->limit(40);
                    }),
                ImageColumn::make('speaker.avatar')
                    ->circular()
                    ->label('')
                    ->defaultImageUrl(function (Talk $record) {
                        return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($record->speaker->name);
                    }),
                TextColumn::make('speaker.name')
                    ->sortable()
                    ->searchable(),
                ToggleColumn::make('new_talk'),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(function ($state) {
                        return $state->getColor();
                    }),
                IconColumn::make('length')
                    ->icon(function ($state) {
                        return match ($state) {
                            TalkLength::NORMAL => 'heroicon-o-megaphone',
                            TalkLength::LIGHTNING => 'heroicon-o-bolt',
                            TalkLength::KEYNOTE => 'heroicon-o-key',
                        };
                    })

            ])
            ->filters([
                TernaryFilter::make('new_talk'),
                SelectFilter::make('status')
                    ->options(TalkStatus::class),
                SelectFilter::make('speaker')
                    ->relationship('speaker', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Filter::make('has_avatar')
                    ->toggle()
                    ->query(function ($query) {
                        return $query->whereHas('speaker', fn ($query) => $query->whereNotNull('avatar'));
                    })
                    ->label('Only speakers that have an avatar'),
            ],
            layout: FiltersLayout::Dropdown
            )
            ->deferFilters(false)
            ->recordActions([
                DeleteAction::make(),
                EditAction::make()->slideOver(),
                ActionGroup::make([
                    Action::make('approve')
                        ->action(function (Talk $record) {
                            $record->approve();
                        })
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->after(function () {
                            Notification::make()
                                ->success()
                                ->title('Talk Approved')
                                ->body('Talk has been approved successfully.')
                                ->send();
                        })
                        ->visible(function (Talk $record) {
                            return $record->status === TalkStatus::SUBMITTED;
                        }),
                    Action::make('reject')
                        ->action(function (Talk $record) {
                            $record->reject();
                        })
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->after(function () {
                            Notification::make()
                                ->danger()
                                ->title('Talk Rejeced')
                                ->body('Talk has been rejected.')
                                ->send();
                        })
                        ->visible(function (Talk $record) {
                            return $record->status === TalkStatus::SUBMITTED;
                        }),
                ]),

            ])
            ->toolbarActions([
                BulkAction::make('approve')
                    ->action(function (Collection $records) {
                       $records->each(fn (Talk $record) => $record->approve());
                    })
                    ->icon('heroicon-o-check-circle')
                    ->successNotificationTitle('Talks Approved'),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('export')
                    ->tooltip('Export visible records')
                    ->label('Export')
                    ->action(function ($livewire) {
                        ray($livewire->getFilteredTableQuery());
                    })
            ]);
    }
}
