<?php

namespace App\Filament\App\Resources\Talks\Pages;

use App\Filament\App\Resources\Talks\TalkResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTalk extends CreateRecord
{
    protected static string $resource = TalkResource::class;
}
