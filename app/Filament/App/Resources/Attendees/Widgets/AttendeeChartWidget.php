<?php

namespace App\Filament\App\Resources\Attendees\Widgets;

use App\Filament\App\Resources\Attendees\Pages\ListAttendees;
use App\Models\Attendee;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AttendeeChartWidget extends ChartWidget
{
    use InteractsWithPageTable;

    protected ?string $heading = 'Attendee Sign Ups';

    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '200px';

    public ?string $filter = 'week';

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last Week',
            'month' => 'Last Month',
            '3months' => 'Last 3 Months'
        ];
    }

    protected function getTablePage(): string
    {
        return ListAttendees::class;
    }

    protected function getData(): array
    {
        $filter = $this->filter;

        $query = $this->getPageTableQuery();
        $query->getQuery()->orders = [];

        match ($filter) {
            'week' => $data = Trend::query($query)
                ->between(now()->subWeek(), now())
                ->perDay()
                ->count(),
            'month' => $data = Trend::query($query)
                ->between(now()->subMonth(), now())
                ->perDay()
                ->count(),
            '3months' => $data = Trend::query($query)
                ->between(now()->subMonths(3), now())
                ->perMonth()
                ->count(),
        };

        return [
            'datasets' => [
                [
                    'label' => 'Signups',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
