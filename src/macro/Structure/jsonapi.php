<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiJsonapiObject',
    /**
     * @param array $expected
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    function ($expected) {
        Assert::assertResponseJsonapiObjectEquals($this, $expected);
    }
);
