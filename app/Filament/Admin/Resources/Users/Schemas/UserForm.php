<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->rules(['required', 'max:255']),
                TextInput::make('email')
                    ->label('Email address')
                    ->rules(fn (string $operation) => [
                        $operation === 'create' ?? 'unique:users,email',
                        'email',
                        'required',
                    ]),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn (string $operation) => $operation === 'create')
                    ->rules(fn (string $operation) =>
                        [
                            $operation === 'create' ? 'required, confirmed' : 'nullable',
                            'min:8',
                            'confirmed'
                        ]),
                TextInput::make('password_confirmation')
                    ->password()
                    ->dehydrated(false)
                    ->rules(fn (string $operation) =>
                    [
                        $operation === 'create' ? 'required, confirmed' : 'nullable',
                        'min:8',
                    ]),
                CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->columns(3)
            ])
            ->columns(1)
            ->inlineLabel();
    }
}
