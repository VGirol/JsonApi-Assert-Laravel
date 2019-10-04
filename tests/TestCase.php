<?php

namespace VGirol\JsonApiAssert\Laravel\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use VGirol\JsonApiAssert\Laravel\JsonApiAssertServiceProvider;
use VGirol\JsonApiAssert\SetExceptionsTrait;

abstract class TestCase extends BaseTestCase
{
    use SetExceptionsTrait;

    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return VGirol\JsonApiAssert\Laravel\JsonApiAssertServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [
            JsonApiAssertServiceProvider::class
        ];
    }
}
