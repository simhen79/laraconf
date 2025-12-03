<?php

namespace App\Filament\Resources\Conferences\Schemas;

use App\Models\Conference;
use Filament\Schemas\Schema;

class ConferenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(Conference::getForm())->columns(1);
    }
}
