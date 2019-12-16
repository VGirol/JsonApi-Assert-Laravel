<?php

namespace VGirol\JsonApiAssert\Laravel;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider for Laravel
 */
class JsonApiAssertServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $list = glob(__DIR__ . '/macro/**/*.php');
        if ($list !== false) {
            foreach ($list as $file) {
                require_once($file);
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
