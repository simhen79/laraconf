<?php

namespace App\Filament\App\Resources\Talks\Schemas;

use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use App\Models\Talk;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TalkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(Talk::getForm());
    }
}
