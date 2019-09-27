<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiUpdated',
    /**
     * @param array<string, mixed> $expected The expected updated resource object
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    function ($expected, $strict = false) {
        Assert::assertIsUpdatedResponse($this, $expected, $strict);
    }
);
