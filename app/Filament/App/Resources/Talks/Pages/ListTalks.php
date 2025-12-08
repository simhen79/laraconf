<?php

namespace App\Filament\App\Resources\Talks\Pages;

use App\Enums\TalkStatus;
use App\Filament\App\Resources\Talks\TalkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListTalks extends ListRecords
{
    protected static string $resource = TalkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->slideOver(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Talks'),
            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', TalkStatus::APPROVED);
                }),
            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', TalkStatus::REJECTED);
                }),
            'submitted' => Tab::make('Submitted')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', TalkStatus::SUBMITTED);
                }),
        ];
    }
}
