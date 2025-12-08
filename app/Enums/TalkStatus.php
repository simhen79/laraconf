<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TalkStatus: string implements HasLabel
{
    case SUBMITTED = 'Submitted';
    case APPROVED = 'Approved';
    case REJECTED = 'Rejected';

    public function getColor(): string {
        return match ($this) {
            self::SUBMITTED => 'primary',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };
    }

    public function getLabel(): string
    {
        return str($this->value)->title();
    }
}
