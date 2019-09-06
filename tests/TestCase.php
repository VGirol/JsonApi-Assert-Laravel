<?php

namespace VGirol\JsonApiAssert\Laravel\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use VGirol\JsonApiAssert\Laravel\JsonApiAssertServiceProvider;
use VGirol\JsonApiAssert\SetExceptionsTrait;
use VGirol\JsonApiFaker\Laravel\Testing\CanCreateFake;

abstract class TestCase extends BaseTestCase
{
    use SetExceptionsTrait;
    use CanCreateFake;

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
