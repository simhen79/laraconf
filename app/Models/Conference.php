<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\App;

class Conference extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'venue_id' => 'integer',
            'is_published' => 'boolean',
            'region' => Region::class,
        ];
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public static function getForm(): array
    {
        return [
            Tabs::make()
                ->tabs([
                    Tabs\Tab::make('Conference Details')
                        ->schema([
                            Section::make('Conference Details')
                                ->description('Details about the conference')
                                ->collapsible()
                                ->columns(2)
                                ->schema([
                                    TextInput::make('name')
                                        ->columnSpanFull()
                                        ->label('Conference Name')
                                        ->hint('The name of the conference')
                                        ->maxLength(60)
                                        ->required(),
                                    MarkdownEditor::make('description')
                                        ->columnSpan(2)
                                        ->required(),
                                    DateTimePicker::make('start_date')
                                        ->native(false)
                                        ->displayFormat('Y-m-d H:i:s')
                                        ->closeOnDateSelection()
                                        ->required(),
                                    DateTimePicker::make('end_date')
                                        ->native(false)
                                        ->displayFormat('Y-m-d H:i:s')
                                        ->closeOnDateSelection()
                                        ->required(),
                                    Fieldset::make('Status')
                                        ->columns(1)
                                        ->schema([
                                            Toggle::make('is_published')
                                                ->label('Published')
                                                ->default(true),
                                            Select::make('status')
                                                ->options([
                                                    'draft' => 'Draft',
                                                    'published' => 'Published',
                                                    'archived' => 'Archived',
                                                ])
                                                ->required()
                                        ])
                                ])
                        ]),
                        Tabs\Tab::make('Location')
                            ->schema([
                                Section::make('Location')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('region')
                                            ->live()
                                            ->enum(Region::class)
                                            ->options(Region::class)
                                            ->native(false)
                                            ->required(),
                                        Select::make('venue_id')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm(Venue::getForm())
                                            ->editOptionForm(Venue::getForm())
                                            ->relationship(
                                                'venue',
                                                'name',
                                                modifyQueryUsing: function (Builder $query, Get $get) {
                                                    return $query->where('region', $get('region'));
                                                })
                                    ])
                            ]),
                        Tabs\Tab::make('Speakers')
                            ->schema([
                                Section::make('Speakers')
                                    ->columns(1)
                                    ->collapsible()
                                    ->schema([
                                        CheckboxList::make('speakers')
                                            ->bulkToggleable()
                                            ->searchable()
                                            ->columns(3)
                                            ->columnSpanFull()
                                            ->label('Speakers')
                                            ->relationship('speakers', 'name')
                                            ->options(Speaker::all()->pluck('name', 'id'))
                                    ])
                            ])
                ]),
            Actions::make([
                Action::make('star')
                    ->label('Fill with Test Data')
                    ->icon('heroicon-o-beaker')
                    ->color('gray')
                    ->action(function ($livewire) {
                        $data = Conference::factory()->make()->toArray();
                        $livewire->form->fill($data);
                    })
            ])
                ->hiddenOn([Operation::Edit, Operation::View])
                ->visible(function () {
                    return App::environment('local');
                })
        ];
    }
}
