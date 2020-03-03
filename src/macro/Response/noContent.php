<?php

use Illuminate\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiNoContent',
    /**
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    function () {
        Assert::assertIsNoContentResponse($this);
    }
);
