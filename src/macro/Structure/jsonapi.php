<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiJsonapiObject',
    /**
     * @param array $expected
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    function ($expected) {
        Assert::assertResponseJsonapiObjectEquals($this, $expected);
    }
);
