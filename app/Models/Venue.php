<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
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
            'region' => Region::class,
        ];
    }

    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->rules(['required', 'string', 'max:255']),
            TextInput::make('city')
                ->rules(['required', 'string', 'max:255']),
            TextInput::make('country')
                ->rules(['required', 'string', 'max:255']),
            TextInput::make('postal_code')
                ->rules(['required', 'string', 'max:255']),
            Select::make('region')
                ->enum(Region::class)
                ->options(Region::class)
                ->rules(['required'])
                ->native(false),
        ];
    }
}
