<?php

namespace App\Filament\App\Resources\Attendees\Schemas;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;

class AttendeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Shout::make('warn-price')
                    ->content('Please note that the ticket price is not final and may change.')
                    ->visible(function (Get $get) {
                        return $get('ticket_cost') > 500;
                    })
                    ->columnSpanFull()
                    ->color(Color::Lime),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('ticket_cost')
                    ->live(true)
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Toggle::make('is_paid')
                    ->required(),
                Select::make('conference_id')
                    ->relationship('conference', 'name')
                    ->required(),
            ]);
    }
}
