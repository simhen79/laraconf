<?php

namespace App\Filament\App\Widgets;

use App\Filament\App\Resources\Attendees\AttendeeResource;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class CustomWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    protected string $view = 'filament.app.widgets.custom-widget';

    protected static ?int $sort = 2;

    public function callNotification(): Action
    {
        return Action::make('callNotification')
            ->button()
            ->color('warning')
            ->label('Send a notification')
            ->action(function() {
                return Notification::make()
                    ->warning()
                    ->title('Notification Title')
                    ->body('Notification Body')
                    ->duration(2000)
                    ->actions([
                        Action::make('gotoattendees')
                            ->button()
                            ->color('primary')
                            ->label('Go to Attendees')
                            ->url(AttendeeResource::getUrl('index')),
                        Action::make('undo')
                            ->link()
                            ->color('gray')
                            ->close()
                    ])
                    ->send();
            });
    }
}
