<?php

namespace App\Models;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
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
            'qualifications' => 'array',
        ];
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->rules('required'),
            TextInput::make('email')
                ->label('Email address')
                ->rules(['required', 'email']),
            Textarea::make('bio')
                ->rules('required')
                ->columnSpanFull(),
            TextInput::make('twitter_handle'),
            CheckboxList::make('qualifications')
                ->columnSpanFull()
                ->searchable()
                ->bulkToggleable()
                ->columns(2)
                ->options([
                    'first_time' => 'First-time Speaker',
                    'laracasts_contributor' => 'Laracasts Contributor',
                    'business_leader' => 'Business Leader',
                ])
        ];
    }
}
