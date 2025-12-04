<?php

namespace App\Filament\App\Resources\Speakers\Pages;

use App\Filament\App\Resources\Speakers\SpeakerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSpeaker extends CreateRecord
{
    protected static string $resource = SpeakerResource::class;
}
