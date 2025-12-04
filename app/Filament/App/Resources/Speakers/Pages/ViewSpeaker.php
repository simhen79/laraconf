<?php

namespace App\Filament\App\Resources\Speakers\Pages;

use App\Filament\App\Resources\Speakers\SpeakerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSpeaker extends ViewRecord
{
    protected static string $resource = SpeakerResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

}
