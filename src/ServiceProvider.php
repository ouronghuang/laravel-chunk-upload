<?php

namespace Orh\LaravelChunkUpload;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/chunk-upload.php', 'chunk-upload');

        $this->app->singleton(ChunkUpload::class, function () {
            return new ChunkUpload();
        });

        $this->app->alias(ChunkUpload::class, 'chunk-upload');
    }

    public function provides()
    {
        return [ChunkUpload::class, 'chunk-upload'];
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/chunk-upload.php' => config_path('chunk-upload.php'),
        ], 'chunk-upload-config');
    }
}
