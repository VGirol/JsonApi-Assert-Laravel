<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiErrorResponse',
    /**
     * @param integer $expectedStatusCode
     * @param array   $expectedErrors     An array of the expected error objects.
     * @param boolean $strict             If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    function ($expectedStatusCode, $expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, $expectedStatusCode, $expectedErrors, $strict);
    }
);
