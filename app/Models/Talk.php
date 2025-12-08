<?php

namespace App\Models;

use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Talk extends Model
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
            'speaker_id' => 'integer',
            'status' => TalkStatus::class,
            'length' => TalkLength::class,
        ];
    }

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public static function getForm($speakerId = null): array
    {
        return [
            TextInput::make('title')
                ->columnSpanFull()
                ->required(),
            RichEditor::make('abstract')
                ->required()
                ->columnSpanFull(),
            Select::make('speaker_id')
                ->relationship('speaker', 'name')
                ->hidden(function () use ($speakerId) {
                    return $speakerId !== null;
                })
                ->required(),
            Select::make('status')
                ->enum(TalkStatus::class)
                ->options(TalkStatus::class)
                ->default(TalkStatus::SUBMITTED),
            Select::make('length')
                ->enum(TalkLength::class)
                ->options(TalkLength::class)
                ->default(TalkLength::NORMAL),
            Toggle::make('new_talk')
                ->default(true),
        ];
    }

    public function approve(): void
    {
        $this->status = TalkStatus::APPROVED;
        //email the speaker etc
        $this->save();
    }

    public function reject(): void
    {
        $this->status = TalkStatus::REJECTED;
        //email the speaker etc
        $this->save();
    }
}
