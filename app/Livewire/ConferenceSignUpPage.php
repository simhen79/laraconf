<?php

namespace App\Livewire;

use App\Models\Attendee;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class ConferenceSignUpPage extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions, InteractsWithSchemas;

    public int $conferenceId;

    public int $price = 5000;

    public function mount()
    {
        $this->conferenceId = 1;
    }

    public function signUpAction(): Action
    {
        return Action::make('signup')
            ->schema([
                TextInput::make('total')
                    ->disabled()
                    ->dehydrated(false)
                    ->extraInputAttributes([
                        'class' => 'border-0 bg-transparent focus:ring-0 focus:border-0 shadow-none',
                    ])
                    ->extraAttributes([
                        'class' => 'border-none',
                    ]),
                Repeater::make('attendees')
                    ->schema(Attendee::getForm())
                    ->columns(2)
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('total', count($get('attendees')) * 500 ?? 0);
                    })
                    ->deleteAction(function (Action $action) {
                        $action->after(function (Get $get, Set $set) {
                            $set('total', count($get('attendees')) * 500 ?? 0);
                        });
                    })
                    ->defaultItems(0),
            ])
            ->slideOver()
            ->action(
                function (array $data) {
                    collect($data['attendees'])->each(function ($attendee) {
                         Attendee::create([
                             'conference_id' => $this->conferenceId,
                             'ticket_cost' => $this->price,
                             'name' => $attendee['name'],
                             'email' => $attendee['email'],
                             'is_paid' => true,
                         ]);
                    });
                }
            )->after(function () {
                Notification::make()
                    ->success()
                    ->title('Success!')
                    ->body(new HtmlString('Your tickets have been purchased successfully.'))
                    ->send();
            });
    }

    public function render()
    {
        return view('livewire.conference-sign-up-page');
    }
}
