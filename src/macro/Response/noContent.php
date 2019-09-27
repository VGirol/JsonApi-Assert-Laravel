<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiNoContent',
    /**
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    function () {
        Assert::assertIsNoContentResponse($this);
    }
);
