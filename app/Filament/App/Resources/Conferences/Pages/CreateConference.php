<?php

namespace App\Filament\App\Resources\Conferences\Pages;

use App\Filament\App\Resources\Conferences\ConferenceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateConference extends CreateRecord
{
    protected static string $resource = ConferenceResource::class;
}
