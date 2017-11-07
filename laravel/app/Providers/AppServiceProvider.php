<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindInterfaces();
    }

    /**
     * Bind interfaces to their implementations for dependency injection
     *
     * @return void
     */
    private function bindInterfaces(): void
    {
        // Stats download service

        $this->app->bind(
            \App\Services\Stats\DownloadService\IDownloadService::class,            // Interface
            \App\Services\Stats\DownloadService\HTTP\DownloadService::class         // Concrete Implementation
        );

        // Stats cleanup service

        $this->app->bind(
            \App\Services\Stats\CleanupService\ICleanupService::class,
            \App\Services\Stats\CleanupService\Mock\CleanupService::class
        );

        // Logging service

        $this->app->bind(
            \App\Services\LoggingService\ILoggingService::class,
            \App\Services\LoggingService\File\LoggingService::class
        );

        // Storage service

        $this->app->bind(
            \App\Services\StorageService\IStorageService::class,
            \App\Services\StorageService\Local\StorageService::class
        );        

        // Config loader service

        $this->app->bind(
            \App\Services\ConfigLoaderService\IConfigLoaderService::class,
            \App\Services\ConfigLoaderService\File\ConfigLoaderService::class
        );
    }
}
