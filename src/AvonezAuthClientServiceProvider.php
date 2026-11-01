<?php

declare(strict_types=1);

namespace AvonezAuthClient;

use AvonezAuthClient\Console\Commands\AvonezAuthClientCommand;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;
use League\Uri\Builder;

class AvonezAuthClientServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/avonez-auth-client.php', 'avonez-auth-client');

        $this->app->singleton(AvonezAuthClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/avonez-auth-client.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'avonez-auth-client');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/avonez-auth-client.php' => config_path('avonez-auth-client.php'),
        ], ['avonez-auth-client', 'avonez-auth-client-config']);

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/avonez-auth-client'),
        ], ['avonez-auth-client', 'avonez-auth-client-views']);

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/avonez-auth-client'),
        ], ['avonez-auth-client', 'avonez-auth-client-assets']);

        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], ['avonez-auth-client', 'avonez-auth-client-migrations']);

        $this->commands([
            AvonezAuthClientCommand::class,
        ]);

        Blueprint::macro('belongsToOrganization', function (bool $nullable = false) {
            $organizationModel = config('avonez-auth-client.organization-model');
            $column = $this->foreignIdFor($organizationModel, 'organization_id');
            if ($nullable) {
                $column->nullable()
                    ->constrained()
                    ->nullOnDelete();
            } else {
                $column->constrained()
                    ->cascadeOnDelete();
            }
        });

        Blueprint::macro('belongsToUser', function (bool $nullable = false) {
            $userModel = config('avonez-auth-client.user-model');
            $column = $this->foreignIdFor($userModel, 'user_id');
            if ($nullable) {
                $column->nullable()
                    ->constrained()
                    ->nullOnDelete();
            } else {
                $column->constrained()
                    ->cascadeOnDelete();
            }
        });
    }
}
