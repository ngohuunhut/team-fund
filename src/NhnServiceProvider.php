<?php

namespace Nhn\Demo;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Str;
use Nhn\Demo\Actions\Gsheet;
use Nhn\Demo\Actions\Message;
use Nhn\Demo\Actions\Money;
use Nhn\Demo\Commands\UpdateMoneyCommand;

class NhnServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/nhn.php',
            'nhn'
        );

        $this->app->bind('update:money', UpdateMoneyCommand::class);

        $this->app->bind(Gsheet::class, function ($app) {
            return new Gsheet(config('nhn.sheet_id'), now()->month, new Money);
        });

        $this->app->bind(Message::class, function ($app) {
            return new Message(config('nhn.folder'));
        });

        $this->commands([
            'update:money'
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/nhn.php' => config_path('nhn.php'),
        ]);


        Collection::macro('nhn', function ($value) {
            return Str::upper($value);
        });
    }
}
