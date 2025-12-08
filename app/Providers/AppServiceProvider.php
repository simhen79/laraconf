<?php

namespace App\Providers;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Actions\CreateAction;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        CreateAction::configureUsing(function (CreateAction $createAction) {
            $createAction
                ->closeModalByEscaping(false)
                ->closeModalByClickingAway(false)
                ->slideOver();
        });

        EditAction::configureUsing(function (EditAction $editAction) {
            $editAction
                ->closeModalByClickingAway(false)
                ->closeModalByEscaping(false)
                ->slideOver();
        });

        ViewAction::configureUsing(function (ViewAction $viewAction) {
            $viewAction
                ->closeModalByClickingAway(false)
                ->closeModalByEscaping(false);
        });
    }
}
